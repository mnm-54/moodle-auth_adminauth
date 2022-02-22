# MOODLE authentication Admin Authentication plugin

- This plugin can be used for 2 way authentication for `admin` users using OTP
- OTP is send to the user's email

## Quick Install:

- Put this entire directory at:

```
PATHTOMOODLE/auth/
```

- Then visit your site notifications page to install the new plugins

## How to use the plugin :

- activate the plugin from

```
Dashboard/Site administration/Plugins/Authentication/Manage authentication
```

- Change authentication method in user's profile in the `Choose an authentication method` drop down field and choose `Admin Authentication`

- Now in login, the plugin will look for `admin` in user's username or email and start the 2 way authentication process
