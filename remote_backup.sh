chmod a+r -R /backup/clients
su ubuntu -c "rsync -Haqz /backup/clients/ ubuntu@b:/backup/clients"