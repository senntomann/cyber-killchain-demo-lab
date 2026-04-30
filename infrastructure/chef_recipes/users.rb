#
# Cookbook:: metasploitable
# Recipe:: users
#
# Copyright:: 2017, Rapid7, All Rights Reserved.

node[:obsolete_users].each do |obsolete_user|
  user obsolete_user do
    action :remove
    manage_home true
  end
end

node[:users].each do |u, attributes|
  user attributes[:username] do
    manage_home true
    password attributes[:password_hash]
    gid 100
    home "/home/#{attributes[:username]}"
    shell '/bin/bash'
  end
end

administrator_members = node[:users].keys.find_all { |user| node[:users][user][:admin] == true }

group 'sudo' do
  action :modify
  members administrator_members.map { |u| node[:users][u][:username] }
  append true
end

administrator_members.each do |administrator|
  username = node[:users][administrator][:username]

  file "/etc/sudoers.d/#{username}" do
    content "#{username} ALL=(ALL) NOPASSWD:ALL\n"
    owner 'root'
    group 'root'
    mode '0440'
  end
end
