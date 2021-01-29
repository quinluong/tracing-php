<?php

namespace Tracing\Enum;

class TagValueEnum {

  const ERROR_TRUE = true;

  const ERROR_FALSE = false;

  const HTTP_METHOD_GET = 'GET';

  const HTTP_METHOD_POST = 'POST';

  const DB_TYPE_REDIS = 'redis';

  const DB_TYPE_MEMCACHED = 'memcached';

  const DB_TYPE_MYSQL = 'mysql';

  const DB_TYPE_MARIADB = 'mariadb';

  const CACHE_STATUS_HIT = 'hit'; // The resource was found in cache

  const CACHE_STATUS_MISS = 'miss'; // The resource was not found in cache and was served from the origin API

  const CACHE_STATUS_EXPIRED = 'expired'; // The resource was found in cache but has since expired and was served from the origin API

  const CACHE_STATUS_STALE = 'stale'; // The resource was served from cache but is expired. Executor couldn't contact the origin API to retrieve the updated resource

  const CACHE_STATUS_BYPASS = 'bypass'; // The resource was served from origin API directly

}
