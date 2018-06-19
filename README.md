# Golem-Port-Check-PHP
PHP script to check what Golem ports are open on a user's network.

This is the script used at https://golem.timjones.id.au/ports

The script now gives feedback in a "Results & Next Steps" section which will vary based on the results of the test.

For checking the different feedback given for different scenarios, you can prevent the script from performing an actual test by setting an override string in the url.

For example, to see what feedback is given for 2 fully open nodes and 1 partially open node, use "?override=21" as follows:

https://golem.timjones.id.au/ports?override=21

# Suggestions / Improvements
Please feel free to submit a pull request if you would like to see any changes or improvements to this script (they will then also be updated on https://golem.timjones.id.au/ports )

