set :application, "Scrum Board"

set :domain,      "util7" #uses ~/.ssh/config
set :deploy_to,   "/home/sites/scrumboard"

set :app_path,    "app"
set :use_composer, true
set :update_vendors, false
set :shared_files,      ["app/config/parameters.yml"]
set :shared_children,     [app_path + "/logs", "node_modules"]

set :scm,         :git
set :branch,      "master"
set :repository,    "https://github.com/DigitalWindow/scrum-board.git"

set :user, "admin"
set :group, "admin"
set :use_sudo, false

set :model_manager, "doctrine"

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

set  :keep_releases,  3

default_run_options[:pty] = true

# Use local keys
set :ssh_options, { :forward_agent => true }

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL

# Set the group correctly
after "deploy", :setup_group
task :setup_group do
  run "cd #{deploy_to}/current"
  #run "setfacl -dR -m u:apache:rwx -m u:`whoami`:rwx #{deploy_to}/current/app/cache #{deploy_to}/current/app/logs"
  #run "setfacl -R -m u:apache:rwX -m u:`whoami`:rwX #{deploy_to}/current/app/cache #{deploy_to}/current/app/logs"
  run "chmod -R a+rw #{deploy_to}/current/app/cache"
  #run "php #{deploy_to}/current/app/console cache:clear --env=prod --no-debug"
  run "php #{deploy_to}/current/app/console assetic:dump --env=prod --no-debug"
end