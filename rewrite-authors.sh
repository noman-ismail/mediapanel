#!/bin/bash
# Script to rewrite git history and change commit authors
# This will change all commits from hassankwl1001 and nomanismail-napollo to Noman Ismail

# Set your correct email here
CORRECT_EMAIL="nomii.uol@gmail.com"
CORRECT_NAME="Noman Ismail"

# Old emails to replace
OLD_EMAIL1="67587657+hassankwl1001@users.noreply.github.com"
OLD_EMAIL2="noman.ismail@napollo.online"

echo "Rewriting git history..."
echo "Changing authors to: $CORRECT_NAME <$CORRECT_EMAIL>"

git filter-branch --env-filter "
OLD_EMAIL1=\"$OLD_EMAIL1\"
OLD_EMAIL2=\"$OLD_EMAIL2\"
CORRECT_NAME=\"$CORRECT_NAME\"
CORRECT_EMAIL=\"$CORRECT_EMAIL\"

if [ \"\$GIT_COMMITTER_EMAIL\" = \"\$OLD_EMAIL1\" ] || [ \"\$GIT_COMMITTER_EMAIL\" = \"\$OLD_EMAIL2\" ]
then
    export GIT_COMMITTER_NAME=\"\$CORRECT_NAME\"
    export GIT_COMMITTER_EMAIL=\"\$CORRECT_EMAIL\"
fi
if [ \"\$GIT_AUTHOR_EMAIL\" = \"\$OLD_EMAIL1\" ] || [ \"\$GIT_AUTHOR_EMAIL\" = \"\$OLD_EMAIL2\" ]
then
    export GIT_AUTHOR_NAME=\"\$CORRECT_NAME\"
    export GIT_AUTHOR_EMAIL=\"\$CORRECT_EMAIL\"
fi
" --tag-name-filter cat -- --branches --tags

echo ""
echo "History rewritten!"
echo "Run: git push --force --all"
echo "Then: git push --force --tags"

