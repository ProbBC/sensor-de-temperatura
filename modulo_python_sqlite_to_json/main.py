import sqlite3
import json

conn = sqlite3.connect('monitortemp.db')

cursor = conn.cursor();

cursor.execute("select * from temperatura;")

strjson = []

for linha in cursor.fetchall():
    print(linha)
    strjson.append(linha)
    stra = json.JSONEncoder().encode(strjson)
    with open('teste.json','w') as json_file:
        json.dump(stra, json_file)

conn.close()
