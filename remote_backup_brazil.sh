chmod a+r -R /backup/clients
su ubuntu -c "rsync -Haqz /backup/clients/ ubuntu@brazil.thirdsectordesign.org:/backup/clients"