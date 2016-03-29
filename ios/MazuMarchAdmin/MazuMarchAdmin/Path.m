//
//  Path.m
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/3/29.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import "Path.h"

@implementation Path

- (Path *) initWithDictionary:(NSDictionary *)dictionary {
    self = [super init];
    if (self) {
        self.id = [dictionary objectForKey:@"i"];
        self.lat = [dictionary objectForKey:@"a"];
        self.lng = [dictionary objectForKey:@"n"];
        self.time = [dictionary objectForKey:@"t"];
        
        self.position = CLLocationCoordinate2DMake([self.lat doubleValue], [self.lng doubleValue]);
    }
    return self;
}
@end
