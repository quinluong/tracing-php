<?php

namespace Tracing;

use OpenTracing\Span;
use OpenTracing\SpanContext;

interface TracerInterface {

  /**
   * Starts and returns a new `Span` representing a unit of work.
   *
   * This method differs from `startSpan` because it uses in-process
   * context propagation to keep track of the current active `Span` (if
   * available).
   *
   * Starting a root `Span` with no casual references and a child `Span`
   * in a different function, is possible without passing the parent
   * reference around:
   *
   *  function handleRequest(Request $request, $userId)
   *  {
   *      $rootSpan = $this->tracer->startActiveSpan('request.handler');
   *      $user = $this->repository->getUser($userId);
   *  }
   *
   *  function getUser($userId)
   *  {
   *      // `$childSpan` has `$rootSpan` as parent.
   *      $childSpan = $this->tracer->startActiveSpan('db.query');
   *  }
   *
   * @param string $operationName
   * @param array|StartSpanOptions $options A set of optional parameters:
   *   - Zero or more references to related SpanContexts, including a shorthand for ChildOf and
   *     FollowsFrom reference types if possible.
   *   - An optional explicit start timestamp; if omitted, the current walltime is used by default
   *     The default value should be set by the vendor.
   *   - Zero or more tags
   *   - FinishSpanOnClose option which defaults to true.
   *
   * @return Scope
   */
  public function startActiveSpan($operationName, $options = []);

  /**
   * Starts and returns a new `Span` representing a unit of work.
   *
   * @param string $operationName
   * @param array|StartSpanOptions $options
   * @return Span
   * @throws InvalidSpanOption for invalid option
   * @throws InvalidReferencesSet for invalid references set
   */
  public function startSpan($operationName, $options = []);

  /**
   * @param SpanContext $spanContext
   * @param string $format
   * @param mixed $carrier
   *
   * @see Formats
   *
   * @throws UnsupportedFormat when the format is not recognized by the tracer
   * implementation
   */
  public function inject(SpanContext $spanContext, $format, &$carrier);

  /**
   * @param string $format
   * @param mixed $carrier
   * @return SpanContext|null
   *
   * @see Formats
   *
   * @throws UnsupportedFormat when the format is not recognized by the tracer
   * implementation
   */
  public function extract($format, $carrier);

  public function checkThenFlush(bool $limitTagSize, bool $limitLogSize);

  /**
   * Allow tracer to send span data to be instrumented.
   *
   * This method might not be needed depending on the tracing implementation
   * but one should make sure this method is called after the request is delivered
   * to the client.
   *
   * As an implementor, a good idea would be to use {@see register_shutdown_function}
   * or {@see fastcgi_finish_request} in order to not to delay the end of the request
   * to the client.
   *
   * @throws Exception
   */
  public function flush();

  public function pause();

  public function resume();

  public function getTraceId(SpanContext $spanContext);

  public function getSpanDuration(Span $span);

  public function getAllSpanDurations();

}
