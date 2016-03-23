//
//  NSString+ActiveSupportInflector.m
//  ActiveSupportInflector
//

//#import "NSString+MSAdditions.h"
#import "ActiveSupportInflector.h"

@implementation NSString (ActiveSupportInflector)

- (NSString *)find:(NSString *)r replace:(NSString *)p {
    NSRegularExpression *regexp = [NSRegularExpression
                                   regularExpressionWithPattern:r
                                   options:0
                                   error:NULL];

    return [regexp
             stringByReplacingMatchesInString:self
             options:0
             range:NSMakeRange(0, self.length)
             withTemplate:p];
}
- (NSString *)camelize {
    return [[[self stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]] find:@"[_\\- ]+" replace:@" "] find:@" +" replace:@""];
}
- (NSString *)ucfirst {
    return [self stringByReplacingCharactersInRange:NSMakeRange(0,1) withString:[[self substringToIndex:1] uppercaseString]];
}
- (NSString *)underscorify {
    return [[[self stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]] find:@"[_\\- ]+" replace:@"_"] find:@"([a-z])([A-Z])" replace:@"$1_$2"];
}

- (NSString *)pluralizeString {
  static ActiveSupportInflector *inflector = nil;
  if (!inflector) {
    inflector = [[ActiveSupportInflector alloc] init];
  }
	
  return [inflector pluralize:self];
}


- (NSString *)singularizeString {
  static ActiveSupportInflector *inflector = nil;
  if (!inflector) {
    inflector = [[ActiveSupportInflector alloc] init];
  }
	
  return [inflector singularize:self];
}

@end
