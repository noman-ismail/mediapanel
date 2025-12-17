# GitHub Setup Guide

Follow these steps to upload your MediaPanel package to GitHub.

## Prerequisites

- Git installed on your system
- GitHub account
- Empty GitHub repository created (e.g., `https://github.com/noman-ismail/mediapanel`)

## Step-by-Step Instructions

### Step 1: Navigate to Package Directory

Open your terminal/command prompt and navigate to the mediapanel directory:

```bash
cd C:\Blog\mediapanel
```

### Step 2: Initialize Git Repository

If git is not already initialized:

```bash
git init
```

### Step 3: Add All Files

Add all files to git staging:

```bash
git add .
```

### Step 4: Create Initial Commit

Commit all files:

```bash
git commit -m "v1.0.0 - Initial release: Laravel MediaPanel package with Tailwind CSS UI"
```

### Step 5: Add Remote Repository

Add your GitHub repository as remote origin. Replace `YOUR_USERNAME` and `YOUR_REPO_NAME` with your actual GitHub username and repository name:

```bash
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git
```

**Example:**
```bash
git remote add origin https://github.com/noman-ismail/mediapanel.git
```

**Or if using SSH:**
```bash
git remote add origin git@github.com:YOUR_USERNAME/YOUR_REPO_NAME.git
```

### Step 6: Rename Branch to Main (if needed)

If your default branch is `master`, rename it to `main`:

```bash
git branch -M main
```

### Step 7: Push to GitHub

Push your code to GitHub:

```bash
git push -u origin main
```

If you're using `master` branch:

```bash
git push -u origin master
```

### Step 8: Verify

Visit your GitHub repository URL to verify all files are uploaded successfully.

## Complete Command Sequence

Here's the complete sequence of commands (copy and paste, replacing YOUR_USERNAME and YOUR_REPO_NAME):

```bash
cd C:\Blog\mediapanel
git init
git add .
git commit -m "v1.0.0 - Initial release: Laravel MediaPanel package with Tailwind CSS UI"
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git
git branch -M main
git push -u origin main
```

## Troubleshooting

### If you get "remote origin already exists" error:

Remove the existing remote and add again:

```bash
git remote remove origin
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git
```

### If you get authentication errors:

1. **For HTTPS:** Use a Personal Access Token instead of password
   - Go to GitHub → Settings → Developer settings → Personal access tokens
   - Generate a new token with `repo` scope
   - Use the token as password when prompted

2. **For SSH:** Set up SSH keys
   ```bash
   ssh-keygen -t ed25519 -C "your_email@example.com"
   ```
   Then add the public key to GitHub → Settings → SSH and GPG keys

### If you need to update existing repository:

```bash
git add .
git commit -m "Update: Your commit message"
git push origin main
```

## Next Steps After Uploading

1. **Add Repository Description:**
   - Go to your GitHub repository
   - Click "Settings" → "General"
   - Add description: "Laravel Media Panel with crop, resize, multi-size images, Blade + Tailwind CSS"

2. **Add Topics/Tags:**
   - Click on the gear icon next to "About"
   - Add topics: `laravel`, `media`, `image`, `upload`, `tailwind`, `blade`, `php`, `package`

3. **Set Up GitHub Actions (Optional):**
   - Add CI/CD workflows for testing
   - Add automated releases

4. **Create Release:**
   - Go to "Releases" → "Create a new release"
   - Tag: `v1.0.0`
   - Title: `v1.0.0 - Initial Release`
   - Description: Copy from CHANGELOG.md

5. **Publish to Packagist:**
   - Once on GitHub, submit your package to Packagist.org
   - Visit https://packagist.org/packages/submit
   - Enter your GitHub repository URL

## Git Configuration (First Time Setup)

If this is your first time using Git, configure your identity:

```bash
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"
```

## Useful Git Commands

```bash
# Check status
git status

# View commit history
git log --oneline

# View remote repositories
git remote -v

# Pull latest changes
git pull origin main

# Create a new branch
git checkout -b feature/new-feature

# Switch branches
git checkout main
```

## Repository URL Format

- **HTTPS:** `https://github.com/USERNAME/REPO.git`
- **SSH:** `git@github.com:USERNAME/REPO.git`

Choose the one that works best for your setup!

