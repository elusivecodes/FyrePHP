# TODO

## Frost

### DateTime

- Add custom language support
- Add custom number locale support
- Finish custom methods
- Finish format methods
- Add static methods

## Fyre

### Component

#### Cache

- Add memcached Driver

#### Image

- Add quality options to config

#### Mail

- Finish SMTP Mail Handler
- Add support for Mail attachments

#### Parser

- Fix Handlers
- Fix Config

#### Session

- Add memcached Driver

### Database

- Add DBInterface, QBInterface, ResultInterface and ForgeInterface
- Fix DB Query Bindings
- Fix *protectIdentifiers* method
- Add SubQueryBuilder

#### QueryBuilder

- Move QueryBuilder into separate class
- Fix *buildWhereFull* method
- Add escaping to *buildWhere*, *buildHaving*, *buildJoinOn* and *buildJoinUsing*
- Move ActiveRecord to QueryBuilder
- Move table parameter to *joinStart*
- Add escape parameter to *having* and *like*

### Engine

- Add Entities, and base Entity class
- Add base Model class, with Entity support
- Add paths Config file
- Add Events, and Events Config file

#### Lang

- Store loaded files by key and retrieve values using *array_get*
- Add IntlMessageFormatter to *get* method

#### Loader

- Add ClassList for faster lookups

#### Request

- Add NegotiateContentType
