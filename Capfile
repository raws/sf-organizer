require "railsless-deploy"
require "shellwords"

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
  task :create_config do
    config_dir_path = File.join(shared_path, "config")
    config_file_path = File.join(config_dir_path, "organizer.json")
    run "mkdir -p #{config_dir_path.shellescape} && touch #{config_file_path.shellescape}"
  end
  
  task :symlink_config do
    original = File.join(shared_path, "config").chomp("/").shellescape
    link = File.join(release_path, "config").chomp("/").shellescape
    run "rm -fr #{link} && ln -fs #{original} #{link}"
  end
  
  task :migrate do; end
  task :restart do; end
end

after "deploy:setup", "deploy:create_config"
after "deploy:symlink", "deploy:symlink_config"
