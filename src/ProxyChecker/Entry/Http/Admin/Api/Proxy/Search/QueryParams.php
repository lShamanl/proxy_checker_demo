<?php

declare(strict_types=1);

namespace App\ProxyChecker\Entry\Http\Admin\Api\Proxy\Search;

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
     *         "id": {"eq": "99337"},
     *         "createdAt": {"range": "2011-06-09 06:37:55,2011-06-28 06:37:55"},
     *         "updatedAt": {"range": "2010-05-25 01:19:22,2010-06-15 01:19:22"},
     *         "ipProxy": {"eq": "rol"},
     *         "ipReal": {"like": "qux"},
     *         "port": {"like": "fred"},
     *         "proxyType": {"eq": "other"},
     *         "proxyStatus": {"eq": "not_work"},
     *         "country": {"like": "bar"},
     *         "region": {"like": "foo"},
     *         "city": {"eq": "osе"},
     *         "timeout": {"eq": "bar"},
     *         "checkList.id": {"eq": "21413"},
     *         "checkList.createdAt": {"range": "1972-04-13 15:43:23,1972-04-28 15:43:23"},
     *         "checkList.updatedAt": {"range": "2021-07-25 17:38:32,2021-08-21 17:38:32"},
     *         "checkList.endAt": {"range": "2012-06-19 13:29:01,2012-07-17 13:29:01"},
     *         "checkList.payload": {"like": "osе"},
     *         "checkList.allIteration": {"like": "bar"},
     *         "checkList.successIteration": {"like": "bar"}
     *     }
     * )
     */
    public Filters $filters;
}
