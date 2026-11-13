#!/bin/bash
# Builds an upload-ready copy of MediCore for InfinityFree.
# Usage:  bash deploy/infinityfree/build.sh
set -e
HERE="$(cd "$(dirname "$0")" && pwd)"
APP="$(cd "$HERE/../.." && pwd)"
OUT="$APP/../infinityfree_build"
SITE="$OUT/htdocs"

echo "==> Building into $OUT"
rm -rf "$OUT"
mkdir -p "$SITE"

rsync -a "$APP/" "$SITE/" \
  --exclude ".git" --exclude ".gitattributes" --exclude ".gitignore" --exclude ".DS_Store" \
  --exclude "/.env" --exclude "/.env.*" \
  --exclude "/deploy" --exclude "/database" \
  --exclude "/test_*.php" --exclude "/screenshots" --exclude "/documentation_assets" \
  --exclude "/documentation.html" --exclude "/demo_walkthrough.html" --exclude "/project_workflow.html" \
  --exclude "/*.md" --exclude "/Jenkinsfile" --exclude "/sonar-project.properties" \
  --exclude "/front-end/hts-cache" \
  --exclude "*.zip" \
  --exclude "/application/logs/log-*" \
  --exclude "/application/cache/sessions/ci_session*" \
  --exclude "/vendor/mpdf/mpdf/ttfonts/Sun-Ext*.ttf" \
  --exclude "/common/assets/fontawesome5pro/metadata"

# Empty, writable folders the app needs
mkdir -p "$SITE/application/cache/sessions" "$SITE/application/logs"
touch "$SITE/application/cache/sessions/index.html"

# Production .env
if [ -f "$HERE/env.production" ]; then
  cp "$HERE/env.production" "$SITE/.env"
  echo "==> .env copied from deploy/infinityfree/env.production"
else
  cp "$HERE/env.infinityfree.example" "$SITE/.env"
  echo "!!  No env.production found: htdocs/.env is the TEMPLATE, edit it before uploading"
fi

# Database dump for phpMyAdmin
python3 "$HERE/clean_sql.py" "$APP/database/hms.sql" "$OUT/hms_infinityfree.sql"

# InfinityFree limits: 10 MB per file, ~30,000 files
BIG=$(find "$SITE" -type f -size +9M)
if [ -n "$BIG" ]; then echo "!!  Files over 10 MB (will be refused):"; echo "$BIG"; fi
echo "==> Files: $(find "$SITE" -type f | wc -l | tr -d ' ')  Size: $(du -sh "$SITE" | cut -f1)"
echo "==> Done. Upload the CONTENT of $SITE into /htdocs with FileZilla."
