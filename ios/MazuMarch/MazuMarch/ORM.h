//
//  ORM.h
//  Maps
//
//  Created by OA Wu on 2015/12/28.
//  Copyright © 2015年 OA Wu. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <sqlite3.h>
#import "NSString+ActiveSupportInflector.h"
#import <objc/runtime.h>

@interface ORM : NSObject

@property long sid;
@property NSUInteger *count;

+ (BOOL)initDB:(NSString *)sqlName;
+ (BOOL)closeDB;
+ (NSMutableArray *)varList:(Class)class;

- (id)init:(NSDictionary *)params;
- (id)initWithSid:(long) sid;
- (id)initWithSid:(long) sid params:(NSDictionary *)params;
- (id)initWithCount:(NSUInteger *)count;

+ (id)create: (NSDictionary *)params;

+ (NSArray *)findAll:(NSDictionary *)conditions;
+ (NSArray *)findAll;

+ (id)findOne;
+ (id)findOne:(NSDictionary *)conditions;

+ (id)find:(NSDictionary *)conditions;
+ (id)find:(NSString *)type conditions:(NSDictionary *)conditions;
+ (id)first;
+ (id)first:(NSDictionary *)conditions;

+ (NSUInteger *)count;
+ (NSUInteger *)count:(NSDictionary *)conditions;

+ (BOOL)updateAll:(NSDictionary *) params where:(NSString *) where;
+ (BOOL)updateAll:(NSDictionary *) params;
- (BOOL)save;

+ (BOOL)deleteAll:(NSString *)where;
+ (BOOL)deleteAll;
- (BOOL)delete;

+ (BOOL)truncate;

- (NSDictionary *)toDictionary;

@end
