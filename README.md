# Golem-Port-Check-PHP
PHP script to check what Golem ports are open on a user's network.

**Note:** This is not an official Golem script. Additionally, this script is provided as-is, to be used at the user's own risk.

This is the script used at https://golem.timjones.id.au/ports

The script now gives feedback in a "Results & Next Steps" section which will vary based on the results of the test.

For checking the different feedback given for different scenarios, you can prevent the script from performing an actual test by setting an override string in the url.

For example, to see what feedback is given for 2 fully open nodes and 1 partially open node, use "?override=21" as follows:

https://golem.timjones.id.au/ports?override=21

# Suggestions / Improvements
Please feel free to submit a pull request if you would like to see any changes or improvements to this script (they will then also be updated on https://golem.timjones.id.au/ports )

# Further Support
Further support for Golem can be obtained via the following official channels:
* [Golem Docs](https://golem.network/documentation/09-common-issues-troubleshooting/port-forwarding-connection-errors/)
* [RocketChat](https://chat.golem.network/channel/testers)
* [Reddit](https://www.reddit.com/r/GolemProject/)
