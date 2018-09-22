#ifndef PHP_CONSTANT_REFS_H
    #define PHP_CONSTANT_REFS_H

    extern zend_module_entry constant_refs_module_entry;
    #define phpext_constant_refs_ptr &constant_refs_module_entry

    #define PHP_CONSTANT_REFS_VERSION "7.3"
    #define PHP_CONSTANT_REFS_EXTNAME "Constant References"

    #ifdef ZTS
        #include "TSRM.h"
    #endif

    #if defined(ZTS) && defined(COMPILE_DL_CONSTANT_REFS)
        ZEND_TSRMLS_CACHE_EXTERN();
    #endif
#endif