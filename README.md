# d2-master-account
Pvpgn master account syestem that allows users to manage multiple in-game accounts from the web.

* How to run in dev environment (locally)

1. Install Docker
2. Clone the repository (or download the source code)
3. Navigate to the directory
4. Open terminal and run:
 - docker compose build --pull --no-cache
 - docker compose up --wait
5. Open https://localhost in your browser 
6. Open http://localhost:54475 for the MailPit (a local mail catcher where you will be able to see all emails sent from the site)