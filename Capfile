require "railsless-deploy"

# Capistrano
set :application, "organizer.sf.blolol.com"
set :repository, "git://github.com/raws/sf-organizer.git"
set :scm, :git
set :deploy_via, :remote_cache
set :use_sudo, false
ssh_options[:forward_agent] = true
default_run_options[:env] = { "PATH" => "/opt/bin:/opt/sbin:$PATH" }

# Server
role :web, "192.168.1.103"

namespace :deploy do
  task :migrate do; end
  task :restart do; end
end
