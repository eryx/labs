

CC=gcc
#CFLAGS=-levent -I /usr/include/libxml2 -lxml2 -L/usr/lib64/ -I /usr/include/mysql `mysql_config --cflags --libs` -O2
CFLAGS=-levent -I /usr/include/libxml2 -lxml2 -L/usr/lib64/ -I /usr/include/mysql -lmysqlclient -luuid -O2

xml: httprqsd.c
	$(CC) -o httprqsd httprqsd.c $(CFLAGS)

clean: httprqsd
	rm -f httprqsd

install: httprqsd
	install $(INSTALL_FLAGS) -m 4755 -o root httprqsd $(DESTDIR)/usr/bin
	
	


