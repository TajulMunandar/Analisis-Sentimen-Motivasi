import subprocess

filename = "moivasi.csv"
search_keyword = "#motivasi lang:id"
limit = 100
twitter_auth_token = "ae46e8064e6634509e8940450b57e1966cd25f18"

command = f'npx tweet-harvest@2.6.1 -o "{filename}" -s "{search_keyword}" --tab "LATEST" -l {limit} --token {twitter_auth_token}'
subprocess.run(command, shell=True)
