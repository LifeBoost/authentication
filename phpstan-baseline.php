<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	'message' => '#^Cannot access property \\$token on App\\\\Domain\\\\ConfirmationToken\\|null\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Domain/User.php',
];
$ignoreErrors[] = [
	'message' => '#^Cannot access property \\$token on App\\\\Domain\\\\ConfirmationToken\\|null\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Infrastructure/Persistence/DoctrineUserRepository.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$id of static method App\\\\SharedKernel\\\\Id\\:\\:fromString\\(\\) expects string, mixed given\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/src/Infrastructure/Persistence/DoctrineUserRepository.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$token of class App\\\\Domain\\\\ConfirmationToken constructor expects string, mixed given\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/src/Infrastructure/Persistence/DoctrineUserRepository.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$value of static method App\\\\Domain\\\\Status\\:\\:from\\(\\) expects int\\|string, mixed given\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/src/Infrastructure/Persistence/DoctrineUserRepository.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$email of class App\\\\Domain\\\\User constructor expects string, mixed given\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/src/Infrastructure/Persistence/DoctrineUserRepository.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$password of class App\\\\Domain\\\\User constructor expects string, mixed given\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/src/Infrastructure/Persistence/DoctrineUserRepository.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#4 \\$firstName of class App\\\\Domain\\\\User constructor expects string, mixed given\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/src/Infrastructure/Persistence/DoctrineUserRepository.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#5 \\$lastName of class App\\\\Domain\\\\User constructor expects string, mixed given\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/src/Infrastructure/Persistence/DoctrineUserRepository.php',
];
$ignoreErrors[] = [
	'message' => '#^Cannot access property \\$headers on Symfony\\\\Component\\\\HttpFoundation\\\\Request\\|null\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Infrastructure/SymfonyUserInformationCollector.php',
];
$ignoreErrors[] = [
	'message' => '#^Unsafe usage of new static\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/SharedKernel/Id.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method object\\:\\:toArray\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/SharedKernel/Messenger/JsonMessageSerializer.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$subject of function str_replace expects array\\|string, string\\|null given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/UI/API/AbstractAction.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$url of class Symfony\\\\Component\\\\HttpFoundation\\\\RedirectResponse constructor expects string, mixed given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/UI/API/ConfirmEmail/ConfirmEmailAction.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$refreshToken of class App\\\\Application\\\\GenerateToken\\\\RefreshTokenGrantType\\\\GenerateTokenRefreshTokenGrantTypeCommand constructor expects string, bool\\|float\\|int\\|string\\|null given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/UI/API/GenerateToken/GenerateAccessTokenAction.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method Symfony\\\\Component\\\\Validator\\\\ConstraintViolationListInterface\\:\\:getIterator\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/UI/API/Middleware/ErrorHandlerMiddleware.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
