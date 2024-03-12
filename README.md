<p> Git </p>



Git init
git add .
git commit -m "Your commit message here"

git remote add origin <repository_url>

<<< create new branch >>>
git checkout -b <branch-name>

git push -u origin <feature/test-branch>

...
git checkout master
git pull master
git marge feature/test-branch
git merge feature/test-branch



// set global veribal
git config --global user.name "userName"
git config --global user.email "usernam@gmail.com"
git config --global user.password "userPassword"
git config --global credential.helper store
git config --list --show-origin

/// for exit :wq


/// for edit config
git config --global --edit
