# Coalition code test by Vicky Jáuregui

This is a tasks management application using Laravel, MySQL, Laravel Mix, Bootstrap and Vagrant for virtualization.

## Test instructions
Create a very simple Laravel web application for task management: 
- Create task (info to save: task name, priority, timestamps) 
- Edit task -Delete task 
- Reorder tasks with drag and drop in the browser. Priority should automatically be updated based on this. #1 priority goes at top, #2 next down and so on. 
- Tasks should be saved to a mysql table.

## Prerequisites
- Install Vagrant

## Installation

```bash
vagrant up
```
Connect into the new virtual machine:
 ```bash
 vagrant ssh
 ```
 and run the following:
```bash
cd code
php artisan generate:key
php artisan migrate
```

### Compile the assets
Run all Laravel Mix tasks:

```npm run dev```

Run all Laravel Mix tasks and minify output:

```npm run production```

## Testing
Inside the virtual machine run:
```bash
php artisan test
```

