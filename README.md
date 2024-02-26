## Setup

- First, clone this repository:
```bash
git clone git@github.com:msiesse/test-laravel.git
```

- Then, go to the directory and initalize the project with sail:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/opt \
    -w /opt \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

- Then, copy the .env.example file to .env:
```bash
cp .env.example .env
```

- Then, generate the application key and create the Unit Test Folder  before launching the application with sail:
```bash
echo "APP_KEY=base64:$(openssl rand -base64 32)" >> .env
mkdir tests/Unit
./vendor/bin/sail up
```

- To have everything works correctly, you need to launch the migrations:
```bash
./vendor/bin/sail artisan migrate
```

## Endpoints

- To create a job of different tasks, you can use the POST Endpoint `/api/tasks` with the following body:
```json
{
    "text": "Some text",
    "tasks": ["call_reason"]
}
```
You can have multiple tasks in the array (`call_reason`, `call_actions`, `call_segments`, `summary`, `satisfaction`)

You'll receive a `job_uuid` in the response that you can use to get the result of the different tasks when it's completed.

- To get the result of a specific job, you can use the GET Endpoint `/api/tasks`
You'll receive a payload liek this:
```json
{
    "job_uuid": "a1b2c3d4e5f6g7h8i9j0",
    "tasks": [
        {
            "type": "call_reason",
            "result": "Some result"
        },
        {
            "type": "call_actions",
            "result": "Some result"
        }, 
        ...
    ],
    "completed": true
}
```
When you retrieve the result, tasks results are deleted from the database.

## Tests
To run the tests, you can use the following command:

```bash
./vendor/bin/sail artisan test
```
