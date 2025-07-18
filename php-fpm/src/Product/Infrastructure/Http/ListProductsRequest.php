<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Infrastructure\Http;

use olml89\MyTheresaTest\Product\Domain\Category;
use olml89\MyTheresaTest\Product\Domain\Price\Currency;
use olml89\MyTheresaTest\Product\Domain\Price\OriginalPrice;
use olml89\MyTheresaTest\Product\Domain\Specification\CategorySpecification;
use olml89\MyTheresaTest\Product\Domain\Specification\ProductAndSpecification;
use olml89\MyTheresaTest\Product\Domain\Specification\ProductLessThanSpecification;
use olml89\MyTheresaTest\Product\Domain\Specification\ProductSpecification;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;

final class ListProductsRequest extends Request implements ServerRequestInterface
{
    public ?int $limit;
    public ?Category $category;
    public ?OriginalPrice $priceLessThan;

    public function __construct(Request $request)
    {
        $this->limit = $this->parseLimit($request);
        $this->category = $this->parseCategory($request);
        $this->priceLessThan = $this->parsePriceLessThan($request);

        parent::__construct(
            $request->getMethod(),
            $request->getUri(),
            new Headers($request->getHeaders()),
            $request->getCookieParams(),
            $request->getServerParams(),
            $request->getBody(),
        );
    }

    public function specification(): ?ProductSpecification
    {
        $specifications = array_filter(
            [
                !is_null($this->category)
                    ? new CategorySpecification($this->category)
                    : null,
                !is_null($this->priceLessThan)
                    ? new ProductLessThanSpecification($this->priceLessThan)
                    : null,
            ],
            fn (?ProductSpecification $specification) => !is_null($specification),
        );

        if (count($specifications) === 0) {
            return null;
        }

        return new ProductAndSpecification(...$specifications);
    }

    private function parseString(Request $request, string $name): ?string
    {
        if ($request->getMethod() !== 'GET') {
            return null;
        }

        if (is_null($param = $request->getQueryParams()[$name] ?? null)) {
            return null;
        }

        if (is_string($param)) {
            return $param;
        }

        return null;
    }

    private function parseInt(Request $request, string $name): ?int
    {
        if (is_null($param = $this->parseString($request, $name))) {
            return null;
        }

        if (filter_var($param, FILTER_VALIDATE_INT) === false) {
            return null;
        }

        return intval($param);
    }

    private function parseLimit(Request $request): ?int
    {
        return $this->parseInt($request, 'limit');
    }

    private function parseCategory(Request $request): ?Category
    {
        if (is_null($param = $this->parseString($request, 'category'))) {
            return null;
        }

        return Category::tryFrom($param);
    }

    private function parsePriceLessThan(Request $request): ?OriginalPrice
    {
        if (is_null($param = $this->parseInt($request, 'priceLessThan'))) {
            return null;
        }

        return new OriginalPrice($param, Currency::EUR);
    }
}
