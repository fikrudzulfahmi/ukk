#!/usr/bin/env bash
# Auto-deploy UKK (CodeIgniter 4, PHP 8.4) di shared hosting.
# Dipanggil oleh GitHub Actions via SSH, atau manual: bash deploy-hosting.sh
set -eu

say() { printf '\n==> %s\n' "$*"; }

# ---------- 0. Lokasi project ----------
SCRIPT_DIR=$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)
APP_PATH="${DEPLOY_PATH:-$SCRIPT_DIR}"
if [ ! -d "$APP_PATH/.git" ]; then
  echo "GAGAL: $APP_PATH bukan repo git"
  exit 1
fi
cd "$APP_PATH"
say "Project: $APP_PATH"

# Lindungi .env hosting (kalau pernah ter-track, reset --hard bisa menimpanya)
ENV_BACKUP=""
if [ -f .env ]; then
  ENV_BACKUP=$(mktemp)
  cp .env "$ENV_BACKUP"
fi

# ---------- 1. Preflight PHP ----------
PHP_BIN=$(command -v php || true)
if [ -z "$PHP_BIN" ]; then
  echo "GAGAL: binary php tidak ditemukan di PATH"
  exit 1
fi
PHP_VER=$("$PHP_BIN" -r 'echo PHP_VERSION;')
say "PHP: $PHP_VER ($PHP_BIN)"
case "$PHP_VER" in
  8.4.*) ;;
  *)
    echo "GAGAL: composer.json butuh PHP ^8.4, hosting menjalankan $PHP_VER."
    echo "Naikkan versi PHP domain ukk.ingintau.my.id ke 8.4 di panel hosting."
    [ -n "$ENV_BACKUP" ] && rm -f "$ENV_BACKUP"
    exit 1
    ;;
esac

# ---------- 2. Sync kode ----------
BRANCH="${DEPLOY_BRANCH:-main}"
PREV=$(git rev-parse --verify --quiet HEAD || echo "")
git fetch --prune origin "$BRANCH"
NEW=$(git rev-parse --verify --quiet "refs/remotes/origin/$BRANCH" || echo "")
if [ -z "$NEW" ]; then
  echo "GAGAL: branch $BRANCH tidak ada di origin"
  [ -n "$ENV_BACKUP" ] && rm -f "$ENV_BACKUP"
  exit 1
fi

if [ "$PREV" = "$NEW" ]; then
  say "Sudah di commit terbaru $(git rev-parse --short HEAD) - tidak ada perubahan kode"
  CHANGED=0
else
  git reset --hard "$NEW"
  git clean -fd \
    -e .env \
    -e vendor \
    -e writable \
    -e public/uploads \
    -e .well-known \
    -e .ftpquota
  CHANGED=1
  say "Update kode: ${PREV:-<kosong>} -> $NEW"
fi

if [ -n "$ENV_BACKUP" ]; then
  if ! cmp -s "$ENV_BACKUP" .env 2>/dev/null; then
    cp "$ENV_BACKUP" .env
    say ".env hosting dipulihkan"
  fi
  rm -f "$ENV_BACKUP"
fi

# ---------- 3. Composer (hanya bila perlu) ----------
NEED_COMPOSER=0
if [ ! -f vendor/autoload.php ]; then
  NEED_COMPOSER=1
fi
if [ "$CHANGED" = "1" ] && [ -n "$PREV" ]; then
  if ! git diff --quiet "$PREV" "$NEW" -- composer.json composer.lock 2>/dev/null; then
    NEED_COMPOSER=1
  fi
fi

if [ "$NEED_COMPOSER" = "1" ]; then
  COMPOSER_BIN=$(command -v composer || true)
  if [ -z "$COMPOSER_BIN" ]; then
    echo "GAGAL: composer tidak ditemukan di hosting"
    exit 1
  fi
  say "composer install --no-dev"
  "$PHP_BIN" "$COMPOSER_BIN" install \
    --no-dev \
    --optimize-autoloader \
    --prefer-dist \
    --no-interaction \
    --no-progress
  say "vendor siap"
fi

# ---------- 4. writable + cache CodeIgniter ----------
mkdir -p writable/cache writable/logs writable/session writable/uploads writable/debugbar
if [ "$CHANGED" = "1" ] || [ "$NEED_COMPOSER" = "1" ]; then
  find writable/cache -mindepth 1 ! -name 'index.html' -delete 2>/dev/null || true
  say "Cache CodeIgniter dibersihkan"
fi

# ---------- 5. Verifikasi ----------
if "$PHP_BIN" spark --version >/dev/null 2>&1; then
  say "CLI spark OK"
else
  echo "PERINGATAN: php spark gagal dijalankan di hosting"
fi

if [ -n "${APP_URL:-}" ]; then
  CODE=$(curl -s -o /dev/null -w '%{http_code}' --max-time 25 "$APP_URL" || echo 000)
  say "Health check $APP_URL -> HTTP $CODE"
fi

say "SELESAI: $(git rev-parse --short HEAD) $(git log -1 --pretty=%s)"
