# PHP Mockery Examples and Simple Explanations

<img src="https://banners.beyondco.de/Mockery%20Examples%20%26%20Explanations%20%20.png?theme=dark&packageManager=&packageName=git+clone+https%3A%2F%2Fgithub.com%2Famyavari%2Fphp-mockery-examples-and-explanations.git&pattern=architect&style=style_1&description=Master+PHP+Mockery+through+practical+test+cases&md=1&showWatermark=1&fontSize=75px&images=https%3A%2F%2Fwww.php.net%2Fimages%2Flogos%2Fnew-php-logo.svg">

![Mockery Version](https://img.shields.io/badge/Mockery-^1.6-blue)
![Latest Tag](https://img.shields.io/github/v/tag/amyavari/php-mockery-examples-and-explanations)
![License](https://img.shields.io/github/license/amyavari/php-mockery-examples-and-explanations)

This repository contains test cases and simple, and deep explanations for the most common features of [Mockery/Mockery](https://github.com/mockery/mockery). Mockery is a simple yet flexible PHP mock object framework for use in unit testing with PHPUnit, PHPSpec, or any other testing framework. It is also the default mock object framework for [Laravel](https://laravel.com/docs/12.x/mocking).

## What Can You Expect from This Repository?

This repository is designed to explain and test some basic concepts and features of Mockery. Inside the [`./tests`](./tests/) directory, you'll find ordered files that explain and test Mockery's features. You can easily run the tests, modify them, and observe the results.

## How to Use This Repository?

1. Clone the repository to your local machine:

```bash
git clone https://github.com/amyavari/php-mockery-examples-and-explanations.git
```

2. Use Composer to install the required dependencies by running the following command:

```bash
composer install
```

It will install `mockery/mockery`, `phpunit/phpunit`, `laravel/pint` and `symfony/var-dumper`.

3. Explore the [`./tests`](./tests/) directory, where each file corresponds to a specific section of Mockery official documentation. Each test file contains multiple test cases which the method names represents the concept it will explain and test.

   - Each test includes doc (`/* */`) comments that explain the concept
   - You can run a specific test to observe its behavior using:

   ```bash
   vendor/bin/phpunit --filter=<TEST_METHOD_NAME>
   ```

   - Feel free to modify the tests as instructed in the doc comments or as needed to explore different scenarios
   - Don't forget to check [`./src`](./src) directory to understand the code being tested

4. Check the output in your command-line interface, including errors, warnings, and `dump` outputs.

## Table of Contents

Each section below links to the relevant part of the [Mockery documentation](https://docs.mockery.io/en/stable/) and the corresponding test file in this repository:

- **[Creating a Mock object](https://docs.mockery.io/en/stable/reference/creating_test_doubles.html#stubs-and-mocks)** → [`A_MakingMock_Test.php`](./tests/A_MakingMock_Test.php)
- **[Creating a Spy object](https://docs.mockery.io/en/stable/reference/creating_test_doubles.html#spies)** → [`A_MakingMock_Test.php`](./tests/A_MakingMock_Test.php)
- **[Creating a Partial Mock](https://docs.mockery.io/en/stable/reference/creating_test_doubles.html#partial-test-doubles)** → [`A_MakingMock_Test.php`](./tests/A_MakingMock_Test.php)
- **[Behavior Modifiers](https://docs.mockery.io/en/stable/reference/creating_test_doubles.html#behavior-modifiers)** → [`A_MakingMock_Test.php`](./tests/A_MakingMock_Test.php)
- **[Mock Final Classes/Methods](https://docs.mockery.io/en/stable/reference/creating_test_doubles.html#proxied-partial-test-doubles)** → [`A_MakingMock_Test.php`](./tests/A_MakingMock_Test.php)
- **[Declaring Method Call Expectations](https://docs.mockery.io/en/stable/reference/expectations.html#declaring-method-call-expectations)** → [`B_Expectations_Part1_Test.php`](./tests/B_Expectations_Part1_Test.php)
- **[Declaring Method Argument Expectations](https://docs.mockery.io/en/stable/reference/expectations.html#declaring-method-argument-expectations)** → [`B_Expectations_Part1_Test.php`](./tests/B_Expectations_Part1_Test.php)
- **[Declaring Return Value Expectations](https://docs.mockery.io/en/stable/reference/expectations.html#declaring-return-value-expectations)** → [`B_Expectations_Part2_Test.php`](./tests/B_Expectations_Part2_Test.php)
- **[Setting Public Properties](https://docs.mockery.io/en/stable/reference/expectations.html#setting-public-properties)** → [`B_Expectations_Part2_Test.php`](./tests/B_Expectations_Part2_Test.php)
- **[Declaring Call Count Expectations](https://docs.mockery.io/en/stable/reference/expectations.html#declaring-call-count-expectations)** → [`B_Expectations_Part3_Test.php`](./tests/B_Expectations_Part3_Test.php)
- **[Multiple Calls with Different Expectations](https://docs.mockery.io/en/stable/reference/expectations.html#multiple-calls-with-different-expectations)** → [`B_Expectations_Part3_Test.php`](./tests/B_Expectations_Part3_Test.php)
- **[Expectation Declaration Utilities](https://docs.mockery.io/en/stable/reference/expectations.html#expectation-declaration-utilities)** → [`B_Expectations_Part3_Test.php`](./tests/B_Expectations_Part3_Test.php)

## Changes

To see updates, what has changed/added, and when, you can check the [CHANGELOG.md](./CHANGELOG.md) file.

## Read Official Documentation

Of course, this repository cannot replace the [official documentation](https://docs.mockery.io/en/stable/index.html) and does not cover all of PHP Mockery's features. To fully understand Mockery's capabilities, we highly encourage you to read the official documentation. Use this repository as a supplementary resource with simple explanations and prepared tests.

## Contributing

Thank you for considering contributing to this repository! If you find any mistakes or want to add more tests, explanations, or anything else that might be useful for others, your pull requests are highly welcome.

## License

**PHP Mockery Examples and Explanations** was created by **[Ali Mohammad Yavari](https://www.linkedin.com/in/ali-m-yavari/)** under the **[MIT license](https://opensource.org/licenses/MIT)**.
