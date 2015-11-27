1. Setup your keys and settings in the keys.cfg file
2. Run scrape.py directly without any arguments.
3. This script collects tweets for all languages using a separate thread (API account) per language.
   The script is mindful of rate-limits so it will pause threads for 15 minutes once reached.
