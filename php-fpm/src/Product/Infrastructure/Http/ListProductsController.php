<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Infrastructure\Http;

use JsonException;
use olml89\MyTheresaTest\Product\Application\Filter;
use olml89\MyTheresaTest\Product\Application\ListProductsUseCase;
use olml89\MyTheresaTest\Product\Application\ProductsPresenter;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

final readonly class ListProductsController
{
    public function __construct(
        private ListProductsUseCase $listProductsUseCase,
    ) {
    }

    /**
     * @throws JsonException
     */
    public function __invoke(ListProductsRequest $request, Response $response): ResponseInterface
    {
        $filter = new Filter(
            limit: $request->limit,
            specification: $request->specification(),
        );

        $products = $this->listProductsUseCase->list($filter);

        $response
            ->getBody()
            ->write(json_encode(new ProductsPresenter(...$products), flags: JSON_THROW_ON_ERROR));

        return $response
            ->withStatus(code: 200)
            ->withHeader('Content-Type', 'application/json');
    }
}
