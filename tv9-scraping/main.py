import re
import requests
import chardet
import urllib
from bs4 import BeautifulSoup
from article import Article
from save_article import SaveArticle
from config import config
import datetime

today = datetime.date.today()
oneDay = datetime.timedelta(days=1)
tomorrow = today+oneDay

URL = 'https://www.yourtv.com.au/guide/tomorrow/'
resp = requests.get(URL)
# parse HTML
soup = BeautifulSoup(resp.text, "html.parser")
channel9soup = soup.find(attrs={"class": "guide__row", "data-channel-number":"9"})
channel9list = channel9soup.find_all(attrs={"class": "guide__row__block"})
# create save manager to handle saving data in database
saveManager = SaveArticle()

for show in channel9list:
    if 'guide__row__block--yesterday' not in show.attrs['class']:
        URL = config['domain'] + show.find("a").get("href")
        articleId = re.findall(r'(\d{4,8})', URL)[0]
        showDateTimeStr = tomorrow.strftime('%Y-%m-%d ') + show.find("p").text
        showDateTime = datetime.datetime.strptime(showDateTimeStr, '%Y-%m-%d %I:%M %p')
        show = Article(articleId, URL, showDateTime)
        saveManager.saveArticle(show)
