

#include <sys/types.h>
#include <sys/time.h>
#include <sys/queue.h>
#include <stdlib.h>

#include <stdbool.h>

#include <string.h>
//#include <getopt.h>
//#include <ctype.h>
#include <stdio.h>
#include <unistd.h>

/*
#include <sys/socket.h>
#include <sys/stat.h>
#include <sys/mman.h>
#include <netinet/in.h>
#include <net/if.h>
#include <arpa/inet.h>
#include <netdb.h>
#include <arpa/inet.h>
#include <fcntl.h>
#include <time.h>
#include <sys/ioctl.h>
#include <errno.h>
#include <assert.h>
#include <signal.h>
*/

#include <err.h>
#include <event.h>
#include <evhttp.h>

#include <libxml/xmlmemory.h>
#include <libxml/parser.h>
#include <mysql/my_global.h>
#include <mysql/mysql.h>
#include <uuid/uuid.h>

#define RQS_VERSION "1.x-dev"

void service_handler(struct evhttp_request *req, void *arg)
{    
    struct evbuffer *buf;
    buf = evbuffer_new();
    
    if (buf == NULL) {
        err(1, "failed to create response buffer");
    }
    
    evhttp_add_header(req->output_headers, "Server", "Service/" RQS_VERSION);
    //evhttp_add_header(req->output_headers, "Content-Type", "text/plain; charset=GB2312");
    evhttp_add_header(req->output_headers, "Keep-Alive", "120");
    
    evbuffer_add_printf(buf, "%s", "test");
    //evbuffer_add_printf(buf, "Requested: %s\n", evhttp_request_uri(req));
    
    evhttp_send_reply(req, HTTP_OK, "OK", buf);

    evbuffer_free(buf);
     
    MYSQL *conn;
    //MYSQL_RES *result;
    //MYSQL_ROW row;
    
    conn = mysql_init(NULL);
    if (conn == NULL) {
        printf("Error %u: %s\n", mysql_errno(conn), mysql_error(conn));
        exit(1);
    }
    
    if (mysql_real_connect(conn, "localhost", "root", "123456", "test", 0, NULL, 0) == NULL) {
        printf("Error %u: %s\n", mysql_errno(conn), mysql_error(conn));
        exit(1);
    }
    
    char *body = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n\
<entry>\n\
    <title>Atom-Powered Robots Run Amok</title>\n\
    <link href=\"http://example.org/2003/12/13/atom03\"/>\n\
    <id>1225c695-cfb8-4ebb-aaaa-80da344efa6a</id>\n\
    <updated>2003-12-13T18:30:02Z</updated>\n\
    <summary>Some text.</summary>\n\
</entry>";
    
    uuid_t uuid;
    uuid_generate(uuid);
    
    char uuid_char[37];
    uuid_unparse(uuid, uuid_char);
    
    char sql[1000];
    sprintf(sql, "INSERT INTO queue (id, body) VALUES ('%s', '%s')", uuid_char, body);

    //mysql_query(conn, "SET AUTOCOMMIT = 0");
    if (mysql_query(conn, sql)) {
        printf("Error %u: %s\n", mysql_errno(conn), mysql_error(conn));
        //exit(1);
    }
    //mysql_query(conn, "commit");
    mysql_close(conn);
}

int main(int argc, char **argv)
{
    int opt;

    char *conf_listen = "0.0.0.0";
    int conf_port = 9527;
    bool conf_daemon = false;
    int conf_timeout = 3;

    while ((opt = getopt(argc, argv, "dh")) != -1) {
        switch (opt) {
        case 'd':
            conf_daemon = true;
            break;
        case 'h':
        default:
            return 1;
        }
    }
    
    if (conf_daemon == true){
        pid_t pid;

        pid = fork();
        if (pid < 0) {
            exit(EXIT_FAILURE);
        }
        
        if (pid > 0) {
            exit(EXIT_SUCCESS);
        }
    }
    
    FILE *fp_pidfile;
    fp_pidfile = fopen("/tmp/service.pid", "w");
    fprintf(fp_pidfile, "%d\n", getpid());
    fclose(fp_pidfile);
    
    //signal(SIGPIPE, SIG_IGN);
    
    
    struct evhttp *httpd;

    event_init();
    httpd = evhttp_start(conf_listen, conf_port);
    if (httpd == NULL) {
        fprintf(stderr, "Error: Unable to listen on %s:%d\n\n", conf_listen, conf_port);
        exit(1);
    }
    evhttp_set_timeout(httpd, conf_timeout);

    /* Set a callback for requests to "/specific". */
    /* evhttp_set_cb(httpd, "/select", select_handler, NULL); */

    /* Set a callback for all other requests. */
    evhttp_set_gencb(httpd, service_handler, NULL);

    event_dispatch();

    /* Not reached in this code as it is now. */
    evhttp_free(httpd);

    return 0;
}
