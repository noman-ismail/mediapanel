@echo off
REM Windows batch script to rewrite git history and change commit authors
REM This will change all commits from hassankwl1001 and nomanismail-napollo to Noman Ismail

echo Rewriting git history...
echo Changing authors to: Noman Ismail ^<nomii.uol@gmail.com^>

git filter-branch --env-filter "set OLD_EMAIL1=67587657+hassankwl1001@users.noreply.github.com && set OLD_EMAIL2=noman.ismail@napollo.online && set CORRECT_NAME=Noman Ismail && set CORRECT_EMAIL=nomii.uol@gmail.com && if %GIT_COMMITTER_EMAIL%==%OLD_EMAIL1% (set GIT_COMMITTER_NAME=%CORRECT_NAME% && set GIT_COMMITTER_EMAIL=%CORRECT_EMAIL%) && if %GIT_COMMITTER_EMAIL%==%OLD_EMAIL2% (set GIT_COMMITTER_NAME=%CORRECT_NAME% && set GIT_COMMITTER_EMAIL=%CORRECT_EMAIL%) && if %GIT_AUTHOR_EMAIL%==%OLD_EMAIL1% (set GIT_AUTHOR_NAME=%CORRECT_NAME% && set GIT_AUTHOR_EMAIL=%CORRECT_EMAIL%) && if %GIT_AUTHOR_EMAIL%==%OLD_EMAIL2% (set GIT_AUTHOR_NAME=%CORRECT_NAME% && set GIT_AUTHOR_EMAIL=%CORRECT_EMAIL%)" --tag-name-filter cat -- --branches --tags

echo.
echo History rewritten!
echo Run: git push --force --all
echo Then: git push --force --tags

