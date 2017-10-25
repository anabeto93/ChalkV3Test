# How to setup Infobip Hook

First of all, you need to be logged in to Infobip portal:
https://portal.infobip.com/login/

Credentials are in 1Password

Go to the account Numbers:
https://portal.infobip.com/settings/6E4F66F33DA8866E417808693830A930/numbers

Select the number you want to receive the SMS for the project and you should see the FALLBACK CONFIGURATION table.
If there is already an entry, edit it. Otherwise create a new one.

Select when you want to active it (usually current date), leave the timezone to `Ghana/Accra(GMT)` and add a forwarding method:


- Type: HTTP
- URL: `https://api.chalkboard.education/external/infobip/hook/{infobip_hook_key*}
- METHOD: POST
- FORMAT: `application/json`

No url parameters are required

The infobip_hook_key is in the parameters.yml

Then, click on save and that's it, the hook is ready to go.

⚠️ Warning: They are no possibility to have two simultaneous actions that redirect to a different hook with the same phone number sadly


The action we just created use the Forward Method described here:
https://dev.infobip.com/docs/forward-received-messages

