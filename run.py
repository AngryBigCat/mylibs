#！/usr/bin/env python3
# -*- coding:utf-8 -*-
from bs4 import BeautifulSoup
import requests
html_template = """
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
{content}
</body>
</html>
"""

r = requests.get('http://www.liaoxuefeng.com/wiki/0014316089557264a6b348958f449949df42a6d3a2e542c000')

soup = BeautifulSoup(r.content, 'html.parser')

menu_tag = soup.find_all(class_ = 'uk-nav uk-nav-side')[1]

urls = []
for li in menu_tag.find_all('li'):
    url = li.a.get('href')
    if not 'http' in url:
        url = ''.join(['http://www.liaoxuefeng.com', url])
    urls.append(url)

num = 1
for url in urls:
	r = requests.get(url)
	soup = BeautifulSoup(r.content, 'html.parser')
	body = soup.find_all(class_ = 'x-content')[0]
	html = html_template.format(content=body)
	html = html.encode('utf-8')
	f_name = '.'.join([soup.find('h4').get_text(), 'html'])		
	if not soup.find('h4').get_text().find('/'):
		f_name = str(num) + f_name
	else:
		f_name = str(num) + f_name.replace('/','-')

	with open(f_name, 'wb') as f:
		f.write(html)
	num += 1
