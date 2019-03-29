import re
from bs4 import BeautifulSoup
import requests
import pymysql.cursors
import datetime
from bs4 import NavigableString, Declaration, Comment

def connectDB(dbInfo):
    try:
        connection = pymysql.connect(
                                    host=dbInfo['host'],
        							user=dbInfo['user'],
        							password=dbInfo['password'],
        							db=dbInfo['db'],
        							charset=dbInfo['charset']
    							)
    except:
        print("Unable to connect to the database!!! Please check your account info and try again!!!")
        return 0;
    return connection
