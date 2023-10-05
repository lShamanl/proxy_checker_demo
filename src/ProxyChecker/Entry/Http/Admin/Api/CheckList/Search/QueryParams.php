<?php

declare(strict_types=1);

namespace App\ProxyChecker\Entry\Http\Admin\Api\CheckList\Search;

use IWD\Symfony\PresentationBundle\Dto\Input\Filters;
use IWD\Symfony\PresentationBundle\Dto\Input\SearchQuery;
use OpenApi\Annotations as OA;

/** @codeCoverageIgnore */
class QueryParams extends SearchQuery
{
    /**
     * @OA\Property(
     *     property="filter",
     *     type="object",
     *     example={
     *         "id": {"eq": "27416"},
     *         "createdAt": {"range": "1970-03-04 19:59:08,1970-03-29 19:59:08"},
     *         "updatedAt": {"range": "1985-05-05 15:01:41,1985-05-07 15:01:41"},
     *         "endAt": {"range": "2007-02-23 06:13:38,2007-03-11 06:13:38"},
     *         "payload": {"like": "osе"},
     *         "allIteration": {"like": "foo"},
     *         "successIteration": {"like": "qux"},
     *         "proxy.id": {"eq": "29989"},
     *         "proxy.createdAt": {"range": "1978-06-04 04:13:45,1978-06-08 04:13:45"},
     *         "proxy.updatedAt": {"range": "2005-06-08 14:36:23,2005-06-29 14:36:23"},
     *         "proxy.ipProxy": {"like": "rol"},
     *         "proxy.ipReal": {"like": "foo"},
     *         "proxy.port": {"like": "bar"},
     *         "proxy.proxyType": {"eq": "socks"},
     *         "proxy.proxyStatus": {"eq": "not_work"},
     *         "proxy.country": {"like": "qux"},
     *         "proxy.region": {"eq": "bar"},
     *         "proxy.city": {"like": "rol"},
     *         "proxy.timeout": {"eq": "bar"}
     *     }
     * )
     */
    public Filters $filters;
}
