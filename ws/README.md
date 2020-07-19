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

These setup instructions are optimized for Mac. Contact seshrs@umich.edu for help setting up Linux/WSL.

### First-time installation

1. Install VirtualBox and Docker.
```console
$ brew cask install virtualbox
$ brew install docker docker-machine docker-compose
```

2. Setup a virtual machine and run a Docker "Hello World" program.
```console
$ docker-machine env default
$ eval "$(docker-machine env default)"
$ docker run hello-world
...
Hello from Docker!
...
$ docker-machine stop default
```

3. Configure your Hosts file with the IP address of the machine.
Find the IP address by observing the output of `docker-machine env`.
Then edit `/etc/hosts`: At the bottom of the file, add the following line.
```
<YOUR_MACHINE_IP_ADDRESS> ws.local www.ws.local
```

4. Clone the repository.

### Everyday development steps

Start the virtual machine and spin up the container.
```console
$ docker-machine start default
$ docker-compose build
$ docker-compose up
```

The site should now be active at [www.ws.local](www.ws.local).

When you're done, don't forget to shut down the virtual machine. First press Control-C and wait for the container to stop. Then run `docker-machine stop default`.

---

## Commands

All the Wolverine Search commands are written in plain PHP, and are all stored under [`ws/search`](https://github.com/seshrs/wolverine-search/tree/master/ws/search). Have a look at the implementations of some of the different commands. 
Each command is a class that implements the interface [`ICommandController`](https://github.com/seshrs/wolverine-search/blob/master/ws/search/__definitions__/ICommandController.php). The following classes are declared for use in the command:

- [`ws/search/__definitions__/Result.php`](https://github.com/seshrs/wolverine-search/blob/master/ws/search/__definitions__/Result.php): An Encapsulation of a Command's result
- [`ws/__util__/Sitevars.php`](https://github.com/seshrs/wolverine-search/blob/master/ws/__util__/Sitevars.php): The dynamic site variables

I recommend searching the [official PHP documentation](http://php.net/manual/en/) and [StackOverflow](https://stackoverflow.com/) if you have questions about standard PHP functions and their usage.

### Updating Existing Commands
Pre-existing commands can always be improved -- the logic for executing the command can be made more efficient, features can be added, etc.
Some commands like `piazza`, `canvas`, etc. require information about classes to generate the correct URL, and this information needs to be updated each semester. I would appreciate any help to improve these commands.

Once you have implemented your command, visit [http://www.ws.local/debug](http://www.ws.local/debug) or [http://www.ws.local/?debug=1](http://www.ws.local/?debug=1) to make sure that when you type different queries, your command behaves as expected. You will have to include information on the tests you conducted before your pull request is accepted.

Don't forget to update the command's documentation! It can be found in the `documentation` folder, which is in the same folder as the command's PHP file. (Visit [http://www.ws.local/list](http://www.ws.local/list) to ensure that the documentation renders correctly.)

### Creating new commands
Creating a command is not that hard! Here are the steps:

1. Create the Command File and run `make build`.
2. Implement and test the command.
3. Create documentation file and run `make build`.
4. Write the documentation, and check that it renders properly.
5. Commit your code and [create a Pull Request](https://guides.github.com/activities/forking/#making-a-pull-request).

**Step 1: Create the Command File**

Use one of the existing folders (or create a new one) and create a file titled `<filename>.command.php`. Create a function that accepts a query and returns a URL, and register the function with the command's keywords.

From your commandline interface, type `vagrant ssh` and then navigate to `/vagrant/ws`. Run the command `make build`.

*You only have to `make build` when you change the name or location of a command/documentation, or if you add a new command/documentation.*

**Step 2: Implement and test the command**

The commands are written in plain PHP. Take inspiration from commands like [`piazza`](https://github.com/seshrs/wolverine-search/tree/master/ws/search/umich_class_tools/Piazza.command.md), [`cg`](https://github.com/seshrs/wolverine-search/tree/master/ws/search/umich_class_tools/CourseGuide.command.md) or [`mfile`](https://github.com/seshrs/wolverine-search/tree/master/ws/search/umich_generic/MFile.command.md).

After you've written the code for the command, visit [http://www.ws.local/?debug=1](http://www.ws.local/?debug=1) and enter different queries that use your command. Ensure that your command behaves as you expect.

**Step 3: Create the documentation file**

Don't forget to add documentation for the command! If it doesn't already exist, create a folder titled `__documentation__` in the same folder that the command's PHP file is located in. Inside this folder, create a file titled `<filename>.command.md`, and write documentation for your command.

*Don't forget to `make build` after you've created the file with the documentation.*

**Step 4: Write the documentation**

Use the Markdown flavor that GitHub uses. (Check out other documentation files for inspiration.)

Visit [http://www.ws.local/list](http://www.ws.local/list) to ensure that the documentation renders correctly.

**Step 5: Create a Pull Request**

This is how you can merge your changes with the public project. Try reading https://guides.github.com/activities/forking/#making-a-pull-request for more information. (Feel free to email me at seshrs@umich.edu if you're stuck at this step.)

### Creating new default commands
This is a more advanced section, and I am yet to write documentation for this. Read more about default commands in the ["about"](https://ws.heliohost.org/about) section of the website. 
If you'd like to see an example, `eecs280` is a default command, located in its own folder.

If you plan to create a new default command, email me at seshrs@umich.edu.

---

## Infrastructure, Logging and Maintenance
I haven't yet written documentation for this section, but let me know if you're interested in helping with this — I'd definitely appreciate this! Email me at seshrs@umich.edu.

---

## Any other form of help
If none of the above sound interesting, but you'd still like to help out, let me know! Email me at seshrs@umich.edu. Thanks a lot :)
