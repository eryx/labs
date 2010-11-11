

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

#include <libxml/xmlmemory.h>
#include <libxml/parser.h>

/**
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

#include <mysql/my_global.h>
#include <mysql/mysql.h>
#include <uuid/uuid.h>

char *parseStory (xmlDocPtr doc, xmlNodePtr cur) 
{
    cur = cur->xmlChildrenNode;
    while (cur != NULL) {

        if ((!xmlStrcmp(cur->name, (const xmlChar *)"id"))) {            
            char *id = (char *)xmlNodeListGetString(doc, cur->xmlChildrenNode, 1);
            //printf("id:%s\n", id);
            return id;
        }
        cur = cur->next;
    }
    
    return "";
}


int main(int argc, char **argv)
{

    MYSQL *conn;
    MYSQL_RES *result;
    MYSQL_ROW row;
    int num_fields;
    int i;

    

    conn = mysql_init(NULL);
    if (conn == NULL) {
        printf("Error %u: %s\n", mysql_errno(conn), mysql_error(conn));
        exit(1);
    }
    
    if (mysql_real_connect(conn, "localhost", "root", "123456", "test", 0, NULL, 0) == NULL) {
        printf("Error %u: %s\n", mysql_errno(conn), mysql_error(conn));
        exit(1);
    }
    
    /* if (mysql_query(conn, "create database test")) {
        printf("Error %u: %s\n", mysql_errno(conn), mysql_error(conn));
        exit(1);
    }*/
    
    char *stat = "CREATE TABLE IF NOT EXISTS `queue` ( \
        `id` VARCHAR(36) NOT NULL, \
        `body` TEXT NULL, \
        PRIMARY KEY (`id`) \
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8";
    if (mysql_query(conn, stat)) {
        printf("Error %u: %s\n", mysql_errno(conn), mysql_error(conn));
        exit(1);
    }
    
    //
    xmlDocPtr doc;
    xmlNodePtr cur;
    //xmlChar *szKey;
    xmlDocPtr docItem;
    xmlNodePtr curItem;
    char *id = "\0";
    FILE *fp;
    char file[100];
    
    
    doc = xmlParseFile("./feed.xml");
    
    
    if (doc == NULL ) {
        fprintf(stderr,"Document not parsed successfully. \n");
        return 1;
    }
    
    cur = xmlDocGetRootElement(doc);
    if (cur == NULL) {
        fprintf(stderr,"empty document\n");
        xmlFreeDoc(doc);
        return 1;
    }
    
    if (xmlStrcmp(cur->name, (const xmlChar *) "feed")) {
        fprintf(stderr,"document of the wrong type, root node != feed");
        xmlFreeDoc(doc);
        return 1;
    }
    
    
    //fp = fopen("/dev/shm/test2.xml", "w");
    //xmlDocDump (fp, doc);
    //fclose(fp);
                    
    //cur = cur->xmlChildrenNode;
    while (cur != NULL) {
        
        //if ((!xmlStrcmp(cur->name, (const xmlChar *)"data"))){
            
            curItem = cur->xmlChildrenNode;
            
            while (curItem != NULL) {
        
                printf("item:%s\n", curItem->name);
                
                if ((!xmlStrcmp(curItem->name, (const xmlChar *)"entry"))){
                
                    id = parseStory(doc, curItem);
                    printf("/dev/shm/%s.xml\n", id);
                    sprintf(file, "/dev/shm/%s.xml", id);
                    
                    docItem = xmlNewDoc(BAD_CAST "1.0");
                    //cur2 = xmlNewNode(NULL, BAD_CAST "root");
                    xmlDocSetRootElement(docItem, curItem);
                    
                    fp = fopen(file, "w");
                    
                    xmlDocDump (fp, docItem);
                    fclose(fp);
                    
                    //stat = sprintf("INSERT INTO queue (id, body) VALUES ('%s', '%s')", id, curItem);
                    //stat = NULL;
//                    xmlChar *sql;
  //                  int size = 0;
    //                xmlDocDumpMemory(docItem, sql, size);
                    
                    xmlChar *buffer = NULL;
                    int bufsize = 0;
                    xmlDocDumpMemory(docItem, &buffer, &bufsize); 
                    //printf( "the   buffer:\n%s\nsize:%d\n ",   buffer,   bufsize);
                    char sql[1000];
                    uuid_t uuid;
                    uuid_generate(uuid);
                    char cuuid[37];
                    uuid_unparse(uuid, cuuid);
                    printf("\n%s\n", cuuid);
                    sprintf(sql, "INSERT INTO queue (id, body) VALUES ('%s', '%s')", cuuid, buffer);
                    //printf((char *)sql);
                    if (mysql_query(conn, sql)) {
                        printf("Error %u: %s\n", mysql_errno(conn), mysql_error(conn));
                        exit(1);
                    }
    
                }
                curItem = curItem->next;
            }
        
            
            //break;
        //}
        
        cur = cur->next;
    }
    
    xmlFreeDoc(doc);

    
    printf("MySQL client version: %s\n", mysql_get_client_info());
    

    mysql_close(conn);
    
    return 0;
}
