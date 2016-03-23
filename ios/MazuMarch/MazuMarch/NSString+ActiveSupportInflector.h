//
//  NSString+ActiveSupportInflector.h
//  ActiveSupportInflector
//

@interface NSString (ActiveSupportInflector)

//    NSLog(@"%@", [[[@"  AppleCare " underscorify] lowercaseString] pluralizeString]);
//    NSLog(@"%@", [[[@"asd_das" singularizeString] camelize] ucfirst]);
- (NSString *)ucfirst;
- (NSString *)camelize;
- (NSString *)underscorify;
- (NSString *)pluralizeString;
- (NSString *)singularizeString;
- (NSString *)find:(NSString *)r replace:(NSString *)p;
+ (NSString *)stringWithFormatWithArrayArgs:(NSString *)format args:(NSArray *)arguments;

@end
