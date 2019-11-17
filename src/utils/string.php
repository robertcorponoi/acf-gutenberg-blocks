<?php

declare(strict_types = 1);

namespace RobertCorponoi\ACFGutenbergBlocks;

/**
 * Takes a string and returns it in kebab case format (ex. hello-world).
 *
 * @param string $input - The string to transform to kebab case.
 *
 * @return string - Returns the kebab case representation of the string.
 */
function toKebabCase($input): string {

  $toLowercase = strtolower($input);

  $kebab = preg_replace('(_|-| )', '-', $toLowercase);

  return $kebab;

}

/**
 * Takes a string and returns it in a user friendly format (PascalCase but with spaces separating words).
 *
 * @param string $input - The string to transform.
 *
 * @return string - Returns the transformed string.
 */
function toUserFriendlyCase($input): string {

  $underscoreToSpace = str_replace('_', ' ', $input);

  return ucwords($underscoreToSpace);

}

