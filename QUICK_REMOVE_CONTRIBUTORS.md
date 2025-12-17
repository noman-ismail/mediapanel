# Quick Guide: Remove Contributors

## Current Contributors Found:
- `hassankwl1001` (67587657+hassankwl1001@users.noreply.github.com)
- `Noman Ismail` (nomii.uol@gmail.com) ✅ **This is you**
- `nomanismail-napollo` (noman.ismail@napollo.online)

## Quick Steps:

### Step 1: Remove Collaborators (5 minutes)

1. Go to: **https://github.com/noman-ismail/mediapanel/settings/access**
2. Click **"Collaborators"** (left sidebar)
3. Remove each contributor:
   - Find `hassankwl1001` → Click **"Remove"**
   - Find `nomanismail-napollo` → Click **"Remove"**
4. Confirm removal

✅ **Done!** They can no longer access your repository.

### Step 2: Rewrite Git History (Optional - Advanced)

⚠️ **Warning:** This rewrites history. Only do this if you want to change commit authors.

**Option A: Use Git Command (Windows PowerShell)**

```powershell
cd C:\Blog\mediapanel

git filter-branch --env-filter "
OLD_EMAIL1='67587657+hassankwl1001@users.noreply.github.com'
OLD_EMAIL2='noman.ismail@napollo.online'
CORRECT_NAME='Noman Ismail'
CORRECT_EMAIL='nomii.uol@gmail.com'

if [ `$GIT_COMMITTER_EMAIL = `$OLD_EMAIL1 ] || [ `$GIT_COMMITTER_EMAIL = `$OLD_EMAIL2 ]
then
    export GIT_COMMITTER_NAME=`$CORRECT_NAME
    export GIT_COMMITTER_EMAIL=`$CORRECT_EMAIL
fi
if [ `$GIT_AUTHOR_EMAIL = `$OLD_EMAIL1 ] || [ `$GIT_AUTHOR_EMAIL = `$OLD_EMAIL2 ]
then
    export GIT_AUTHOR_NAME=`$CORRECT_NAME
    export GIT_AUTHOR_EMAIL=`$CORRECT_EMAIL
fi
" --tag-name-filter cat -- --branches --tags

# Force push (WARNING: Overwrites remote)
git push --force --all
git push --force --tags
```

**Option B: Use Git Bash (Easier)**

If you have Git Bash installed:

```bash
cd /c/Blog/mediapanel

git filter-branch --env-filter '
OLD_EMAIL1="67587657+hassankwl1001@users.noreply.github.com"
OLD_EMAIL2="noman.ismail@napollo.online"
CORRECT_NAME="Noman Ismail"
CORRECT_EMAIL="nomii.uol@gmail.com"

if [ "$GIT_COMMITTER_EMAIL" = "$OLD_EMAIL1" ] || [ "$GIT_COMMITTER_EMAIL" = "$OLD_EMAIL2" ]
then
    export GIT_COMMITTER_NAME="$CORRECT_NAME"
    export GIT_COMMITTER_EMAIL="$CORRECT_EMAIL"
fi
if [ "$GIT_AUTHOR_EMAIL" = "$OLD_EMAIL1" ] || [ "$GIT_AUTHOR_EMAIL" = "$OLD_EMAIL2" ]
then
    export GIT_AUTHOR_NAME="$CORRECT_NAME"
    export GIT_AUTHOR_EMAIL="$CORRECT_EMAIL"
fi
' --tag-name-filter cat -- --branches --tags

# Force push
git push --force --all
git push --force --tags
```

### Step 3: Verify

```bash
# Check commit authors (should only show Noman Ismail)
git log --format='%aN <%aE>' | sort -u

# Check on GitHub
# Visit: https://github.com/noman-ismail/mediapanel/graphs/contributors
```

## Recommended Approach

**For most cases:**
1. ✅ Remove collaborators via GitHub Settings (Step 1)
2. ✅ Leave commit history as-is (normal practice)

**Only rewrite history if:**
- You absolutely need to change commit authors
- Repository is private or not widely shared
- You understand force push risks

## What Each Step Does

- **Step 1 (Remove Collaborators):** Removes their access - they can't push/pull anymore
- **Step 2 (Rewrite History):** Changes commit authors - removes them from contributors list
- **Step 3 (Verify):** Confirms changes worked

## After Removing Contributors

Your repository will show:
- ✅ Only `noman-ismail` as contributor (if you rewrite history)
- ✅ Or all 3 contributors but only you have access (if you just remove collaborators)

Both approaches are valid - choose based on your needs!

