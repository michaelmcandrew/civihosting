chmod a+r -R /backup/clients
su ubuntu -c "rsync -Havz /backup/clients/ ubuntu@bolivia.thirdsectordesign.org:/backup/clients"