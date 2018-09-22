#ifdef HAVE_CONFIG_H
    #include "config.h"
#endif

#include "php.h"
#include "ext/standard/info.h"
#include "php_constant_refs.h"

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

void constant_refs_define(zend_string *name, zval *value){
    zend_constant constant;
    constant.name = zend_string_copy(name);
    ZVAL_COPY(&constant.value, value);
    ZEND_CONSTANT_SET_FLAGS(&constant, CONST_CS, 0);
    zend_register_constant(&constant);
}

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(constant_refs_define_arg_info, 0, 2, _IS_BOOL, 0)
    ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
    ZEND_ARG_INFO     (0, value)
ZEND_END_ARG_INFO()

PHP_FUNCTION(define){
    zend_string *name = NULL;
    zval *value = NULL;
    if(zend_parse_parameters(ZEND_NUM_ARGS(), "Sz", &name, &value) == FAILURE){
        RETURN_FALSE;
    }
    constant_refs_define(name, value);
    RETURN_TRUE;
}

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

PHP_RINIT_FUNCTION(constant_refs){
    #if defined(COMPILE_DL_CONSTANT_REFS) && defined(ZTS)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
    return SUCCESS;
}

PHP_MINFO_FUNCTION(constant_refs){
    php_info_print_table_start();
    php_info_print_table_header(2, "Constant References", "enabled");
    php_info_print_table_end();
}

const zend_function_entry constant_refs_functions[] = {
    ZEND_NS_FE("Netmosfera\\ConstantReferences", define, constant_refs_define_arg_info)
    PHP_FE_END
};

zend_module_entry constant_refs_module_entry = {
    STANDARD_MODULE_HEADER,
    PHP_CONSTANT_REFS_EXTNAME,
    constant_refs_functions,
    NULL,
    NULL,
    PHP_RINIT(constant_refs),
    NULL,
    PHP_MINFO(constant_refs),
    PHP_CONSTANT_REFS_VERSION,
    STANDARD_MODULE_PROPERTIES
};

#ifdef COMPILE_DL_CONSTANT_REFS
    #ifdef ZTS
        ZEND_TSRMLS_CACHE_DEFINE();
    #endif
    ZEND_GET_MODULE(constant_refs)
#endif
