PHP_ARG_ENABLE(constant-refs, whether to enable Constant References support,
[  --enable-constant-refs   Enable Constant References support])

if test "$PHP_CONSTANT_REFS" != "no"; then
  PHP_NEW_EXTENSION(constant_refs, constant_refs.c, $ext_shared,, -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1)
fi