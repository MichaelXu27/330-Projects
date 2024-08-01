import sys, os
import re
#expects filename as argument and it prints a usage message if the argument is not present
if len(sys.argv) < 2:
    sys.exit(f"Usage: {sys.argv[0]} filename")

filename = sys.argv[1]
# if the file does not exist then it throws this error
if not os.path.exists(filename):
    sys.exit(f"Error: File '{sys.argv[1]}' not found")
class Player:
    def __init__(self, name, at_bat, hits):
        self.name = name
        self.at_bat = at_bat
        self.hits = hits
    def updateStats(self, at_bat, hits):
        self.at_bat += at_bat
        self.hits += hits
    def batting_avg(self):
        if self.at_bat == 0:
            return 0.0
        return self.hits / self.at_bat
        

player_regex = re.compile(r"(.+?) batted (\d+) times with (\d+) hits and \d+ runs")

players = {}
with open(filename) as f:
    for line in f:
        content = line.strip()
        match = player_regex.match(content)
        if match:
            name, at_bat, hits = match.groups()
            at_bat = int(at_bat)
            hits = int(hits)

            if name not in players:
                players[name] = Player(name, at_bat, hits)
            else:
                players[name].updateStats(at_bat, hits)

players = sorted(players.values(), key=lambda player: player.batting_avg(), reverse=True)

for player in players:
    print(f"{player.name}: {player.batting_avg():.3f}")
