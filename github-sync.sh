#!/usr/bin/env bash
# GitHub Auto-Sync Script for CasaSlot
# Pushes all changes directly from the casino dir to https://github.com/Kanpary/CasaSlot

set -e

CASINO_DIR="/home/runner/workspace/artifacts/php-casino"
REPO_URL="https://${GITHUB_TOKEN}@github.com/Kanpary/CasaSlot.git"

echo "[github-sync] Starting sync to GitHub..."

if [ -z "$GITHUB_TOKEN" ]; then
    echo "[github-sync] ERROR: GITHUB_TOKEN not set. Cannot sync."
    exit 1
fi

cd "$CASINO_DIR"

# Initialize git repo inside casino dir if not yet done
if [ ! -d ".git" ]; then
    echo "[github-sync] Initializing local git repo..."
    git init
    git remote add origin "$REPO_URL"
else
    git remote set-url origin "$REPO_URL"
fi

git config user.email "casaslot-bot@replit.com"
git config user.name "CasaSlot Replit Bot"

# Stage all tracked/new files (respects .gitignore)
git add -A

# Check if there is anything to commit
if git diff --cached --quiet; then
    echo "[github-sync] Nothing to commit. Already up to date."
else
    COMMIT_MSG="Auto-sync from Replit: $(date '+%Y-%m-%d %H:%M:%S')"
    git commit -m "$COMMIT_MSG"
    git push -u origin HEAD:main 2>&1 || git push -u origin HEAD:master 2>&1
    echo "[github-sync] Pushed successfully: $COMMIT_MSG"
fi
