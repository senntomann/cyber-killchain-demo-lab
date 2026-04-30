#
# Cookbook:: metasploitable
# Recipe:: cron_privesc
#

directory '/opt/sidlab' do
  owner 'root'
  group 'root'
  mode '0755'
end

file '/opt/sidlab/maintenance.sh' do
  content "#!/bin/sh\ndate >/tmp/sidlab-maintenance.last\n"
  owner 'root'
  group 'www-data'
  mode '0775'
end

file '/etc/cron.d/sidlab-maintenance' do
  content "SHELL=/bin/sh\nPATH=/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin\n* * * * * root /opt/sidlab/maintenance.sh\n"
  owner 'root'
  group 'root'
  mode '0644'
end
