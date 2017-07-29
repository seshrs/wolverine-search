# Development Guide | Wolverine Search
Thanks for your interest in contributing to Wolverine Search! If you have any questions about this guide, feel free to email me at seshrs@umich.edu.

Click one of the links to learn more about how you can help.

<ol>
  <li><a href="#local-development-guide">Local Development Setup</a></li>
  <li><a href="#commands">Commands</a>
    <ol>
      <li><a href="#updating-existing-commands">Updating existing commands</a></li>
      <li><a href="#creating-new-commands">Creating new commands</a></li>
      <li><a href="#creating-new-default-commands">Creating new Default Commands</li>
    </ol>
  </li>
  <li><a href="#infrastructure-logging-and-maintenance">Infrastructure, Logging and Maintenance</a></li>
  <li><a href="#any-other-form-of-help">Any other form of help</a></li>
</ol>

---

## Local Development Guide
Follow these steps to get Wolverine Search to work on your local computer. This will make it easier for you to make changes to the
code and instantly see the results of your changes, and to make sure that they work as intended.

### Installing prerequisites

1. Install [VirtualBox](https://www.virtualbox.org/wiki/Downloads).
2. *[Windows only]* Install [Git Bash](https://github.com/git-for-windows/git/releases/latest). You can use the default/recommended settings.
3. Install [Vagrant](https://www.vagrantup.com/downloads.html).

### Configure your Hosts file
1. Open your commandline interface (Git Bash or Terminal).
2. You need to edit the file `/etc/hosts`, and you'll need administroator access for that. One way to do this:
    - Type `sudo nano /etc/hosts` and type your password.
    - Scroll to the bottom, and add this line at the end of the file
      ```
      192.168.56.101 ws.dev www.ws.dev
      ```
    - Type `Control-x` (to exit), then type `y` (to save), then press `Enter` (to close the editor).all

### Clone, install, build and go!
1. Navigate to some folder where you plan to store the Wolverine Search files. (For instance, `~/Documents/Projects`.)
2. Clone the repository. Type `git clone [repository URL]`. You can get this information from the [main repository page](https://github.com/seshrs/wolverine-search) by clikcing the "Clone or Download" button.
3. Navigate inside the folder that was just created.
4. Type `vagrant up`. This may take a while, so make sure to wait for this step to complete before proceeding.
5. Once the virtual machine has been installed and booted up, type `vagrant ssh`. This lets you manipulate the same repository through the virtual machine.
6. Navigate to the folder `/vagrant`. The files here are from the repository you just cloned, and are shared with your local machine. Any changes you make here will also be reflected in the folder on your local machine.
7. Navigate to the folder `/vagrant/ws`. These are the files that actually run the Wolverine Search website.
8. Type `make init`. When prompted, type `yes`.
9. Type `exit` to exit the virtual machine.
10. Visit `www.ws.dev` in your browser. You are now running a copy of Wolverine Search on your local machine!

### Switch off the VM when not in use
When you're done developing, type `vagrant halt` in your commandline interface to switch off the virtual machine. 
When you want to begin developing again, type `vagrant up` to switch it back on.

---

## Commands

All the Wolverine Search commands are written in plain PHP, and are all stored under [`ws/search/commands`](https://github.com/seshrs/wolverine-search/tree/master/ws/search/commands). Have a look at the implementations of some of the different commands. 
Each command is simply a function that accepts a query as a parameter and returns a URL. The command is registered using the globally available function, and has access to the variable from `ws/sitevars.php` and [`ws/search/helpers.php`](https://github.com/seshrs/wolverine-search/blob/master/ws/search/helpers.php). 
I recommend searching the [official PHP documentation](http://php.net/manual/en/) and [StackOverflow](https://stackoverflow.com/) if you have questions about PHP functions and their usage.

### Updating Existing Commands
Pre-existing commands can always be improved. The logic for executing the command can be made more efficient. 
Some commands like `piazza`, `canvas`, etc. require information about classes to generate the correct URL, and this information needs to be updated each semester.

Once you have implemented your command, visit [http://www.ws.dev/?debug=1](http://www.ws.dev/?debug=1) to make sure that when you type different queries, your command behaves as expected. You will have to include information on the tests you conducted before your pull request is accepted.

Don't forget to update the command's documentation! It can be found in the `__documentation__` folder, which is in the same folder as the command's PHP file. (Visit [http://www.ws.dev/list](http://www.ws.dev/list) to ensure that the documentation renders correctly.)

### Creating new commands
Creating a command is not that hard!

Use one of the existing folders (or create a new one) and create a file titled `<filename>.command.php`. Create a function that accepts a query and returns a URL, and register the function with the command's keywords.

From your commandline interface, type `vagrant ssh` and then navigate to `/vagrant/ws`. Run the command `make build`.

Then visit [http://www.ws.dev/?debug=1](http://www.ws.dev/?debug=1) and enter different queries that use your command. Ensure that your command behaves as you expect.

Don't forget to add documentation for the command! If it doesn't already exist, create a folder titled `__documentation__` in the same folder that the command's PHP file is located in. Inside this folder, create a file titled `<filename>.command.md`, and write documentation for your command. (Check out other documentation files for inspiration.) 
Visit [http://www.ws.dev/list](http://www.ws.dev/list) to ensure that the documentation renders correctly.

### Creating new default commands
This is a fairly advanced section, and I am yet to write documentation for this. Read more about default commands in the ["about"](http://www.ws.dev/about) section. 
If you'd like to see an example, `eecs280` is a default command, located in its own folder.

If you plan to create a new default command, email me at seshrs@umich.edu.

---

## Infrastructure, Logging and Maintenance
I haven't yet written documentation for this section, but let me know if you're interested in helping with this — I'd definitely appreciate this! Email me at seshrs@umich.edu.

---

## Any other form of help
If none of the above sound interesting, but you'd still like to help out, let me know! Email me at seshrs@umich.edu. Thanks a lot :)
