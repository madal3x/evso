Event sourcing and CQRS proof-of-concept

One bounded context that allows to create rooms and reserve seats for it.

There are some reusable components implemented to support event sourcing development (in /common):
- an event store for MySQL
- an optimistic concurrency implementation with versioned aggregates and configurable event conflict resolution
- base classes for event sourced entities and commands

Limitations:
- incomplete tests
- lacks a read model

if used in multi bounded contexts setting, it should also have:
- command deduplication
- event (re)ordering
