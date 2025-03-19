# PHP Mockery Examples and Simple Explanations

This repository contains test cases and simple explanations for the most common features of [Mockery/Mockery](https://github.com/mockery/mockery). Mockery is a simple yet flexible PHP mock object framework for use in unit testing with PHPUnit, PHPSpec, or any other testing framework. It is also the default mock object framework for [Laravel](https://laravel.com/docs/12.x/mocking).

## What Can You Expect from This Repository?

This repository is designed to explain and test some basic concepts and features of Mockery. Inside the `./tests` directory, you'll find ordered files that explain and test Mockery's features. You can easily run the tests, modify them, and observe the results.

## How to Use This Repository?

1. Clone the repository to your local machine using the clone button.
2. Use Composer to install the required dependencies:

   - `mockery/mockery`
   - `phpunit/phpunit` (as necessary packages)
   - `laravel/pint` (optional, for code formatting)

   Run the following command:

```bash
composer install
```

3. Read the documentation for each file or section you want to learn about in the `./tests` directory, and check the main classes in the `./src` directory.  
   The name of each test method represents the concept it will explain and test. Test the code by running the following command:

```bash
vendor/bin/phpunit --filter=<TEST_METHOD_NAME>
```

4. Check the output in your command-line interface, including errors, warnings, and `var_dump` outputs.

## Read Official Documentation

Of course, this repository cannot replace the [official documentation](https://docs.mockery.io/en/stable/index.html) and does not cover all of PHP Mockery's features. To fully understand Mockery's capabilities, we highly encourage you to read the official documentation. Use this repository as a supplementary resource with simple explanations and prepared tests.

## Contributing

Thank you for considering contributing to this repository! If you find any mistakes or want to add more tests, explanations, or anything else that might be useful for others, your pull requests are highly welcome.

## License

**PHP Mockery Examples and Explanations** was created by **[Ali Mohammad Yavari](https://www.linkedin.com/in/ali-m-yavari/)** under the **[MIT license](https://opensource.org/licenses/MIT)**.
