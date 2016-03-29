//
//  ORM.m
//  Maps
//
//  Created by OA Wu on 2015/12/28.
//  Copyright © 2015年 OA Wu. All rights reserved.
//

#import "ORM.h"

@implementation ORM


static sqlite3 *db = nil;

+ (BOOL)initDB:(NSString *)sqlName {
    if (db) return YES;

    NSFileManager *fm = [NSFileManager new];
    NSString *src = [[NSBundle mainBundle] pathForResource:sqlName ofType:@"sqlite"];
    NSString *dst = [NSString stringWithFormat:@"%@/Documents/%@.sqlite", NSHomeDirectory(), sqlName];

    if (src == nil) return NO;
    if ([fm fileExistsAtPath:dst]) [fm removeItemAtPath:dst error:nil];
    
    [fm copyItemAtPath:src toPath:dst error:nil];
    
    if (![fm fileExistsAtPath:dst]) return NO;
    if (sqlite3_open([dst UTF8String], &db) != SQLITE_OK) db = nil;
    
    return YES;
}

+ (BOOL)closeDB {
    if (!db) return YES;
    
    sqlite3_close(db);
    db = nil;
    
    if (!db) return YES;
    else return NO;
}

+ (NSMutableArray *)varList:(Class)class exception:(NSArray *)exception {
    unsigned int count;

    NSMutableArray *list = [NSMutableArray new];
    Ivar *vars = class_copyIvarList(class, &count);
    NSString *temp;
    for (NSUInteger i=0; i<count; i++) {
        temp = [[NSString stringWithFormat:@"%s", ivar_getName(vars[i])] find:@"_" replace:@""];
        if (!exception || ([exception indexOfObject:temp] == NSNotFound)) {
            [list addObject:temp];
        }
        
    }
    return list;
}
+ (NSMutableArray *)varList:(Class)class {
    return [self varList:class exception:nil];
}

- (id)init:(NSDictionary *)params{
    self = [super init];

    if (self)
        for (NSString *key in params)
            if ([self respondsToSelector:NSSelectorFromString(key)])
                [self setValue:[params objectForKey:key] forKey:key];
    
    self.sid = (long)[[params objectForKey:@"id"] integerValue];

    return self;
}

- (id)initWithSid:(long) sid {
    self = [super init];
    if (self) [self setSid:sid];
    return self;
}

- (id)initWithSid:(long) sid params:(NSDictionary *)params {
    self = [super init];
    if (self) {
        [self setSid:sid];

        for (NSString *key in params)
            if ([self respondsToSelector:NSSelectorFromString(key)])
                [self setValue:[params objectForKey:key] forKey:key];
        
        
        self.sid = (long)[params objectForKey:@"id"];

    }
    return self;
}

- (id)initWithCount:(NSUInteger *)count {
    self = [super init];
    if (self) self.count = count;
    return self;
}

+ (id)create: (NSDictionary *)params {
    if (!db) return nil;
    
    NSString *keyFields = [NSString stringWithFormat:@"'%@'", [[params allKeys] componentsJoinedByString:@"', '"]];
    NSString *valueFields = [NSString stringWithFormat:@"'%@'", [[params allValues] componentsJoinedByString:@"', '"]];

    const char *sql = [[NSString stringWithFormat:@"INSERT INTO %@ (%@) VALUES (%@)", [[NSStringFromClass([self class]) lowercaseString] pluralizeString], keyFields, valueFields] cStringUsingEncoding:NSASCIIStringEncoding];

    sqlite3_stmt *statement;
    sqlite3_prepare(db, sql, -1, &statement, NULL);
    if (sqlite3_step(statement) != SQLITE_DONE) return nil;
    sqlite3_finalize(statement);
    
    return [[self alloc] initWithSid:(long)sqlite3_last_insert_rowid(db) params: params];
}

+ (NSArray *)findAll:(NSDictionary *)conditions {
    if (!db) return nil;

    conditions = [[NSMutableDictionary alloc] initWithDictionary:conditions copyItems:YES];
    NSMutableArray *select;
    
    if (![conditions objectForKey:@"select"]) {
        select = [NSMutableArray new];
        [select addObjectsFromArray:@[@"id"]];
        [select addObjectsFromArray:[self varList:[self class]]];
        [conditions setValue:[select componentsJoinedByString:@", "] forKey:@"select"];
    } else {
        select = (NSMutableArray *)[[[conditions objectForKey:@"select"] find:@", *" replace:@","] componentsSeparatedByString:@","];
    }
    
    if ([conditions objectForKey:@"limit"] && [conditions objectForKey:@"offset"])
        [conditions setValue:[NSString stringWithFormat:@"%@,%@", [conditions objectForKey:@"offset"], [conditions objectForKey:@"limit"]] forKey:@"limit"];
    else if ([conditions objectForKey:@"limit"])
        [conditions setValue:[NSString stringWithFormat:@"%@", [conditions objectForKey:@"limit"]] forKey:@"limit"];
    else
        [conditions setValue:nil forKey:@"limit"];
    
    const char *selectSql = [[NSString stringWithFormat:@"SELECT %@ FROM %@%@%@%@%@%@",
                              [conditions objectForKey:@"select"] ? [conditions objectForKey:@"select"] : @"*",
                              [[NSStringFromClass([self class]) lowercaseString] pluralizeString],
                              [conditions objectForKey:@"where"] ? [NSString stringWithFormat:@" WHERE %@", [conditions objectForKey:@"where"]] : @"",
                              [conditions objectForKey:@"group"] ? [NSString stringWithFormat:@" GROUP BY %@", [conditions objectForKey:@"group"]] : @"",
                              [conditions objectForKey:@"having"] ? [NSString stringWithFormat:@" HAVING %@", [conditions objectForKey:@"having"]] : @"",
                              [conditions objectForKey:@"order"] ? [NSString stringWithFormat:@" ORDER BY %@", [conditions objectForKey:@"order"]] : @"",
                              [conditions objectForKey:@"limit"] ? [NSString stringWithFormat:@" LIMIT %@", [conditions objectForKey:@"limit"]] : @""
                              ] cStringUsingEncoding:NSASCIIStringEncoding];

    sqlite3_stmt *statement;
    sqlite3_prepare(db, selectSql, -1, &statement, NULL);
    
    NSMutableArray *row = [NSMutableArray new];
    for (int i = 0; sqlite3_step(statement) == SQLITE_ROW; i++) {
        NSMutableDictionary *column = [NSMutableDictionary new];

        for (int j = 0; j < [select count]; j++)
            [column setValue:[NSString stringWithCString:(char *)sqlite3_column_text(statement, j) encoding:NSUTF8StringEncoding] forKey:[select objectAtIndex:j]];
        
        if ([column objectForKey:@"COUNT(id)"])
            [row addObject:[[self alloc] initWithCount:(NSUInteger *)[[column objectForKey:@"COUNT(id)"] integerValue]]];
        else
            [row addObject:[[self alloc] init:[NSDictionary dictionaryWithDictionary:column]]];
    }
    sqlite3_finalize(statement);
    return row;
}

+ (NSArray *)findAll {
    return [self findAll:nil];
}

+ (id)findOne {
    return [self findOne:nil];
}

+ (id)findOne:(NSDictionary *)conditions {
    id objs = [self findAll:conditions];
    return [objs firstObject];
}

+ (id)find:(NSDictionary *)conditions {
    return [self findAll:conditions];
}

+ (id)find:(NSString *)type conditions:(NSDictionary *)conditions {
    return [type isEqualToString:@"one"] ? [self findOne:conditions] : [self findAll:conditions];
}

+ (id)first {
    return [self find:@"one" conditions:nil];
}

+ (id)first:(NSDictionary *)conditions {
    return [self find:@"one" conditions:conditions];
}

+ (NSUInteger *)count {
    ORM* obj = [[self findAll:@{@"select": @"COUNT(id)"}] firstObject];
    return (NSUInteger *)obj.count;
}

+ (NSUInteger *)count:(NSDictionary *)conditions {
    conditions = [[NSMutableDictionary alloc] initWithDictionary:conditions copyItems:YES];
    [conditions setValue:@"COUNT(id)" forKey:@"select"];
    ORM* obj = [[self findAll:conditions] firstObject];
    return (NSUInteger *)obj.count;
}

+ (BOOL)updateAll:(NSDictionary *) params where:(NSString *) where {
    if (!db) return NO;
    
    NSMutableArray *vars = [self varList:[self class]],
    *set = [NSMutableArray new];
    
    for (NSString *key in params)
        if ((int)[vars indexOfObject:key] > -1)
            [set addObject:[NSString stringWithFormat:@"'%@'='%@'", key, [params objectForKey:key]]];
    
    const char *updateSql = [[NSString stringWithFormat:@"UPDATE %@ SET %@%@", [[NSStringFromClass([self class]) lowercaseString] pluralizeString], [set componentsJoinedByString:@", "], [where length] > 0 ? [NSString stringWithFormat:@" WHERE %@", where] : @""] cStringUsingEncoding:NSASCIIStringEncoding];
    sqlite3_stmt *statement;
    sqlite3_prepare(db, updateSql, -1, &statement, NULL);
    if (sqlite3_step(statement) != SQLITE_DONE) return NO;
    sqlite3_finalize(statement);
    
    return YES;
}

+ (BOOL)updateAll:(NSDictionary *) params {
    return [self updateAll:params where:nil];
}

- (BOOL)save {
    if (!(db && [self sid])) return NO;

    NSMutableArray *vars = [ORM varList:[self class]];
    NSMutableDictionary *params = [NSMutableDictionary new];
    
    for (NSString *key in vars)
        if ([self respondsToSelector:NSSelectorFromString(key)])
            [params setValue:[self valueForKey:key] forKey:key];
    
    return [[self class] updateAll:params where:[NSString stringWithFormat:@"id = %ld", [self sid]]];
}

+ (BOOL)deleteAll:(NSString *)where {
    if (!db) return NO;
    
    const char *deltetSql = [[NSString stringWithFormat:@"DELETE FROM %@%@", [[NSStringFromClass([self class]) lowercaseString] pluralizeString], [where length] > 0 ? [NSString stringWithFormat:@" WHERE %@", where] : @""] cStringUsingEncoding:NSASCIIStringEncoding];
    sqlite3_stmt *statement;
    sqlite3_prepare(db, deltetSql, -1, &statement, NULL);
    if (sqlite3_step(statement) != SQLITE_DONE) return NO;
    sqlite3_finalize(statement);
    
    return YES;
}

+ (BOOL)deleteAll {
    return [self deleteAll:nil];
}

- (BOOL)delete {
    if (!(db && [self sid])) return NO;
    return [[self class] deleteAll:[NSString stringWithFormat:@"id = %ld", [self sid]]];
}
+ (BOOL)truncate {
    if (!db) return NO;
    if (![self deleteAll]) return NO;
    
    const char *deltetSql = [[NSString stringWithFormat:@"DELETE FROM sqlite_sequence WHERE name = '%@'", [[NSStringFromClass([self class]) lowercaseString] pluralizeString]] cStringUsingEncoding:NSASCIIStringEncoding];
    sqlite3_stmt *statement;
    sqlite3_prepare(db, deltetSql, -1, &statement, NULL);
    if (sqlite3_step(statement) != SQLITE_DONE) return NO;
    sqlite3_finalize(statement);
    
    return YES;
}

- (NSDictionary *)toDictionary {
    if (!db) return nil;
    
    NSMutableArray *vars = [ORM varList:[self class]];
    NSMutableDictionary *params = [NSMutableDictionary new];
    
    for (NSString *key in vars)
        if ([self respondsToSelector:NSSelectorFromString(key)])
            [params setValue:[self valueForKey:key] forKey:key];
    

    [params setValue:[self valueForKey:@"sid"] forKey:@"id"];
//    for (NSString *key in @[@"id"])
//        if ([self respondsToSelector:NSSelectorFromString(key)])
//            [params setValue:[self valueForKey:key] forKey:key];
    
    return params;
}
@end
