# Remove Contributors from GitHub Repository

## Current Contributors
Based on your repository, you have 3 contributors:
1. `hassankwl1001`
2. `noman-ismail` (you)
3. `nomanismail-napollo`

## Step 1: Remove Collaborators (Required)

### Via GitHub Web Interface:

1. **Go to Repository Settings:**
   - Navigate to: https://github.com/noman-ismail/mediapanel/settings/access

2. **Remove Collaborators:**
   - Click on **"Collaborators"** in the left sidebar
   - Find `hassankwl1001` → Click **"Remove"** or **"X"**
   - Find `nomanismail-napollo` → Click **"Remove"** or **"X"**
   - Confirm removal for each

3. **Verify:**
   - Go back to your repository main page
   - Check the Contributors section
   - Only `noman-ismail` should remain

## Step 2: Rewrite Git History (Optional - Advanced)

⚠️ **Warning:** This rewrites git history and requires force push. Only do this if:
- Repository hasn't been shared widely
- You understand the implications
- You have permission to rewrite history

### Option A: Change Author for All Commits

```bash
cd C:\Blog\mediapanel

# Install git-filter-repo (recommended) or use git filter-branch
# For Windows, you may need to use git filter-branch instead

git filter-branch --env-filter '
OLD_EMAIL1="67587657+hassankwl1001@users.noreply.github.com"
OLD_EMAIL2="noman.ismail@napollo.online"
CORRECT_NAME="Noman Ismail"
CORRECT_EMAIL="your-email@example.com"

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

# Force push (WARNING: This overwrites remote history)
git push --force --all
git push --force --tags
```

### Option B: Use git-filter-repo (Better Tool)

```bash
# Install git-filter-repo first
pip install git-filter-repo

# Change author for specific emails
git filter-repo --email-callback '
    old_emails = [b"67587657+hassankwl1001@users.noreply.github.com", b"noman.ismail@napollo.online"]
    if email in old_emails:
        return b"your-email@example.com"
    return email
' --name-callback '
    old_names = [b"hassankwl1001", b"nomanismail-napollo"]
    if name in old_names:
        return b"Noman Ismail"
    return name
'

# Force push
git push --force --all
```

## Step 3: Update Your Git Config

Set your identity for future commits:

```bash
cd C:\Blog\mediapanel

# Set your name and email
git config user.name "Noman Ismail"
git config user.email "your-email@example.com"

# Or set globally
git config --global user.name "Noman Ismail"
git config --global user.email "your-email@example.com"
```

## Recommended Approach

**For most cases, just removing collaborators is enough:**

1. ✅ Remove collaborators via GitHub Settings (Step 1)
2. ✅ Update git config for future commits
3. ✅ Leave commit history as-is (this is normal practice)

**Only rewrite history if:**
- You absolutely need to change commit authors
- Repository is private or not widely shared
- You understand the risks

## Verify Changes

After removing collaborators:

```bash
# Check git config
git config user.name
git config user.email

# Check commit authors
git log --format='%aN <%aE>' | sort -u

# View contributors on GitHub
# Visit: https://github.com/noman-ismail/mediapanel/graphs/contributors
```

## Important Notes

1. **Removing Collaborators:** This removes their access but doesn't remove their commits from history
2. **Commit History:** Commits remain with original author info (this is normal)
3. **Contributors List:** GitHub shows contributors based on commits. To remove them from the list, you need to rewrite history
4. **Force Push:** Only use `--force` if you're sure - it overwrites remote history

## Quick Summary

**Simple (Recommended):**
1. Go to: https://github.com/noman-ismail/mediapanel/settings/access
2. Remove collaborators `hassankwl1001` and `nomanismail-napollo`
3. Done! (Their commits remain but they can't contribute anymore)

**Advanced (Rewrite History):**
1. Remove collaborators (Step 1)
2. Rewrite git history (Step 2)
3. Force push
4. Update git config (Step 3)

